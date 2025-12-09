<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;
use App\Models\Peripheral;
use App\Models\ComponentParts;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use function activity;

class UnitAssignParts extends Component
{
    use WithPagination;

    protected $queryString = [];
    public $searchPeripherals = '';
    public $searchComponents = '';
    public $unitId;
    public $unit;

    public string $tab = 'peripherals';
    public ?string $selectedType = null;
    public ?string $selectedPart = null;

    // Assignments (temporary in create mode)
    public $selectedPeripherals = []; // [type => peripheral_id]
    public $selectedComponents = [];  // [part => component_id]

    // -----------------------------------------------------------------------
    // INLINE FORM PROPERTIES
    // -----------------------------------------------------------------------
    public bool $showInlineForm = false;
    public ?string $inlineModelType = null;      // 'component' or 'peripheral'
    public ?string $inlineSelectedPart = null;   // Part name / type
    public $inline_brand;
    public $inline_model;
    public $inline_serial_number;
    public $inline_room_id;
    public $inline_capacity;
    public $inline_clock_speed;
    public $inline_size;
    public $inline_connection_type;

    // Temporary storage for inline-created items before saving parent unit
    public $tempComponents = [];
    public $tempPeripherals = [];

    protected $listeners = [
        'showAssignModal' => 'open'
    ];

    public $partIcons = [
        //components
        'CPU' => 'images/icons/CPU.png',
        'RAM' => 'images/icons/ram.png',
        'PSU' => 'images/icons/PSU.png',
        'GPU' => 'images/icons/GPU.png',
        'UPS' => 'images/icons/UPS.png',
        'AVR' => 'images/icons/AVR.png',
        'Motherboard' => 'images/icons/motherboard.png',
        'Storage' => 'images/icons/storage.png',
        'Casing' => 'images/icons/CASE.png',
        'Cooler' => 'images/icons/Cooler.png',

        //peripherals
        'Monitor' => 'images/icons/display.png',
        'Keyboard' => 'images/icons/keyboard.png',
        'Mouse' => 'images/icons/mouse.png',
        'Headset' => 'images/icons/headset.png',
        'Speaker' => 'images/icons/speaker.png',
        'Camera' => 'images/icons/camera.png',
    ];

    public $allPeripheralTypes = ['Monitor', 'Keyboard', 'Mouse', 'Headset', 'Speaker', 'UPS', 'AVR'];
    public $allComponentParts = ['CPU', 'Motherboard', 'RAM', 'GPU', 'PSU', 'Storage', 'Casing', 'Cooler'];

    // -----------------------------------------------------------------------
    // EVENT EMITTERS FOR PARENT FORM
    // -----------------------------------------------------------------------
    public function updatedSelectedPeripherals()
    {
        if (is_null($this->unitId)) {
            $this->dispatch('unitAssignmentsUpdated', [
                'type' => 'peripherals',
                'data' => $this->selectedPeripherals
            ]);
        }
    }

    public function updatedSelectedComponents()
    {
        if (is_null($this->unitId)) {
            $this->dispatch('unitAssignmentsUpdated', [
                'type' => 'components',
                'data' => $this->selectedComponents
            ]);
        }
    }

    // -----------------------------------------------------------------------
    // MOUNT
    // -----------------------------------------------------------------------
    public function mount($unitId = null)
    {
        $this->unitId = $unitId;

        if ($this->unitId) {
            // EDIT MODE
            $this->unit = SystemUnit::with(['peripherals', 'components'])->findOrFail($unitId);

            foreach ($this->unit->peripherals as $p) {
                $this->selectedPeripherals[$p->type] = $p->id;
            }
            foreach ($this->unit->components as $c) {
                $this->selectedComponents[$c->part] = $c->id;
            }
        } else {
            // CREATE MODE
            $this->unit = new SystemUnit();
            $this->selectedPeripherals = [];
            $this->selectedComponents = [];
        }
    }
    // Add these to your UnitAssignParts class



    public function getAvailablePeripheralsProperty()
    {
        $available = [];
        $roomId = $this->unitId ? SystemUnit::find($this->unitId)?->room_id : null;

    
        foreach ($this->allPeripheralTypes as $type) {

            $query = Peripheral::query()
                ->where('type', $type)
                ->where('status', 'Available') // ✅ Only show Available
                ->whereNull('system_unit_id'); // ✅ Must not be assigned to any unit


            // room filter
            if ($roomId) {
                $query->where(function ($q) use ($roomId) {
                    $q->where('room_id', $roomId)
                        ->orWhereNull('room_id');
                });
            }

            // search
            if ($this->searchPeripherals) {
                $search = "%{$this->searchPeripherals}%";
                $query->whereAny(
                    ['brand', 'model', 'serial_number'],
                    'like',
                    $search
                );
            }

            // pagination per-type
            $available[$type] = $query->paginate(5, ['*'], $type . '_page');
        }

        return $available;
    }
    public function getAvailableComponentsProperty()
    {
        $available = [];
        $roomId = $this->unitId ? SystemUnit::find($this->unitId)?->room_id : null;

        foreach ($this->allComponentParts as $part) {

            $query = ComponentParts::query()
                ->where('part', $part)
                ->where('status', 'Available') // ✅ Only show Available
                ->whereNull('system_unit_id'); // ✅ Must not be assigned to any unit

            // Room filter
            if ($roomId) {
                $query->where(function ($q) use ($roomId) {
                    $q->where('room_id', $roomId)
                        ->orWhereNull('room_id');
                });
            }

            // Search
            if ($this->searchComponents) {
                $search = "%{$this->searchComponents}%";
                $query->where(function ($q) use ($search) {
                    $q->where('brand', 'like', $search)
                        ->orWhere('model', 'like', $search)
                        ->orWhere('capacity', 'like', $search)
                        ->orWhere('serial_number', 'like', $search);
                });
            }

            // Pagination
            $available[$part] = $query->paginate(5, ['*'], $part . '_page');
        }

        return $available;
    }






    // -----------------------------------------------------------------------
    // INLINE FORM METHODS
    // -----------------------------------------------------------------------
    public function addInlineForm($model, $part)
    {
        $this->inlineModelType = $model; // component or peripheral
        $this->inlineSelectedPart = $part;
        $this->showInlineForm = true;

        // Clear previous inputs
        $this->inline_brand = $this->inline_model = $this->inline_serial_number = null;
        $this->inline_capacity = $this->inline_clock_speed = $this->inline_size = $this->inline_connection_type = null;
        $this->inline_room_id = null;
    }

    public function saveInlineItem()
    {
        if ($this->inlineModelType === 'component') {

            $item = [
                'part' => $this->inlineSelectedPart,   // ✅ COMPONENT USES part
                'brand' => $this->inline_brand,
                'model' => $this->inline_model,
                'serial_number' => $this->inline_serial_number,
                'room_id' => $this->inline_room_id,
                'capacity' => $this->inline_capacity,
                'speed' => $this->inline_clock_speed,
                'size' => $this->inline_size,
            ];

            $this->tempComponents[] = $item;
            $this->dispatch('tempComponentAdded', $item);

        } else {

            $item = [
                'type' => $this->inlineSelectedPart,   // ✅ THIS FIXES YOUR SQL ERROR
                'brand' => $this->inline_brand,
                'model' => $this->inline_model,
                'serial_number' => $this->inline_serial_number,
                'room_id' => $this->inline_room_id,
                'connection_type' => $this->inline_connection_type,
            ];

            $this->tempPeripherals[] = $item;
            $this->dispatch('tempPeripheralAdded', $item);

            // ✅ SEND TYPE TO PARENT
            $this->dispatch('peripheralTypeSelected', $this->inlineSelectedPart);
        }

        // ✅ VISUAL FEEDBACK
        $this->dispatch('swal', [
            'toast' => true,
            'icon' => 'success',
            'title' => "{$this->inlineSelectedPart} added successfully",
            'timer' => 2000,
        ]);

        $this->showInlineForm = false;
    }





    public function isTempAdded(string $model, string $part): bool
    {
        if ($model === 'component') {
            return collect($this->tempComponents)->contains('part', $part);
        }

        return collect($this->tempPeripherals)->contains('type', $part);
    }

    public function removeTempComponent($index)
    {
        unset($this->tempComponents[$index]);
        $this->tempComponents = array_values($this->tempComponents);
        $this->dispatch('remove-temp-component', ['index' => $index]);
    }

    public function removeTempPeripheral($index)
    {
        unset($this->tempPeripherals[$index]);
        $this->tempPeripherals = array_values($this->tempPeripherals);
        $this->dispatch('remove-temp-peripheral', ['index' => $index]);
    }

    // -----------------------------------------------------------------------
    // ASSIGN / UNASSIGN PERIPHERALS & COMPONENTS (same as before)
    // -----------------------------------------------------------------------
    public function assignSelected($type, $peripheralId)
    {
        $peripheral = Peripheral::find($peripheralId);
        $this->selectedPeripherals[$type] = $peripheral->id;

        if ($this->unitId && $peripheral && $this->unit) {
            $unit = SystemUnit::find($this->unitId);
            $peripheral->update([
                'system_unit_id' => $this->unitId,
                'room_id' => $unit->room_id,
                'status' => 'In Use',
            ]);

            activity()->causedBy(Auth::user())
                ->performedOn($unit)
                ->withProperties([
                    'type' => $type,
                    'peripheral_id' => $peripheral->id,
                    'peripheral_serial' => $peripheral->serial_number,
                    'unit_name' => $unit->name,
                ])
                ->log("Assigned peripheral ({$peripheral->serial_number}) to '{$unit->name}'");

            $this->dispatch('swal', [
                'title' => 'Peripheral Assigned!',
                'text' => "{$peripheral->serial_number} successfully assigned to {$unit->name}.",
            ]);

            $this->verifyUnitStatus();
        } else {
            $this->updatedSelectedPeripherals();
        }
    }

    public function unassign($type)
    {
        $peripheralId = $this->selectedPeripherals[$type] ?? null;
        $peripheral = $peripheralId ? Peripheral::find($peripheralId) : null;
        $this->selectedPeripherals[$type] = null;

        if ($this->unitId && $peripheral && $this->unit) {
            $unit = SystemUnit::find($this->unitId);
            $peripheral->update([
                'system_unit_id' => null,
                'room_id' => null,
                'status' => 'Available',
            ]);

            activity()->causedBy(Auth::user())
                ->performedOn($unit)
                ->withProperties([
                    'type' => $type,
                    'peripheral_id' => $peripheral->id,
                    'peripheral_serial' => $peripheral->serial_number,
                    'unit_name' => $unit->name,
                ])
                ->log("Unassigned peripheral ({$peripheral->serial_number}) from '{$unit->name}'");

            $this->dispatch('swal', [
                'title' => 'Peripheral Unassigned!',
                'text' => "{$peripheral->serial_number} has been unassigned from {$unit->name}.",
            ]);

            $this->verifyUnitStatus();
        } else {
            $this->updatedSelectedPeripherals();
        }
    }

    public function assignComponent($part, $componentId)
    {
        $component = ComponentParts::findOrFail($componentId);
        $this->selectedComponents[$part] = $component->id;

        if ($this->unitId && $component && $this->unit) {
            $unit = SystemUnit::findOrFail($this->unitId);

            $component->update([
                'system_unit_id' => $this->unitId,
                'room_id' => $unit->room_id,
                'status' => 'In Use',
            ]);

            activity()->causedBy(Auth::user())
                ->performedOn($unit)
                ->withProperties([
                    'item' => $component->serial_number,
                    'part' => $part,
                    'component_id' => $componentId,
                ])
                ->log("Assigned component ({$component->serial_number}) to '{$unit->name}'");

            $this->dispatch('swal', [
                'title' => 'Component Assigned!',
                'text' => "{$component->serial_number} successfully assigned to {$unit->name}.",
            ]);

            $this->verifyUnitStatus();
        } else {
            $this->updatedSelectedComponents();
        }
    }

    public function unassignComponent($part)
    {
        $componentId = $this->selectedComponents[$part] ?? null;
        $component = $componentId ? ComponentParts::find($componentId) : null;
        $this->selectedComponents[$part] = null;

        if ($this->unitId && $component && $this->unit) {
            $unit = SystemUnit::find($this->unitId);
            $component->update([
                'system_unit_id' => null,
                'room_id' => null,
                'status' => 'Available',
            ]);

            activity()->causedBy(Auth::user())
                ->performedOn($unit)
                ->withProperties([
                    'item' => $component->serial_number,
                    'part' => $part,
                    'component_id' => $componentId,
                ])
                ->log("Unassigned component ({$component->serial_number}) from '{$unit->name}'");

            $this->dispatch('swal', [
                'title' => 'Component Unassigned!',
                'text' => "{$component->serial_number} has been unassigned from {$unit->name}.",
            ]);

            $this->verifyUnitStatus();
        } else {
            $this->updatedSelectedComponents();
        }
    }

    public function verifyUnitStatus()
    {
        if (is_null($this->unitId)) {
            $this->dispatch('swal', [
                'title' => 'Selection Updated',
                'text' => 'Temporary assignments saved to form data.',
                'icon' => 'info'
            ]);
            return;
        }

        $unit = SystemUnit::with(['components', 'peripherals'])->find($this->unitId);
        $result = $unit->checkOperationalStatus();

        if ($result['status'] === 'Non-operational') {
            $missingList = '';
            if (!empty($result['missing']['components'])) {
                $missingList .= "Missing Components:\n - " . implode("\n - ", $result['missing']['components']) . "\n\n";
            }
            if (!empty($result['missing']['peripherals'])) {
                $missingList .= "Missing Peripherals:\n - " . implode("\n - ", $result['missing']['peripherals']);
            }
            $this->dispatch('swal', [
                'title' => 'Unit Not Operational',
                'text' => $missingList,
                'icon' => 'warning'
            ]);
            return;
        }
        $this->dispatch('swal', [
            'title' => 'Unit Operational!',
            'text' => 'All required components and peripherals are installed.',
            'icon' => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.system-units.unit-assign-parts', [
            'availablePeripherals' => $this->availablePeripherals,
            'availableComponents' => $this->availableComponents,
            'partIcons' => $this->partIcons,
        ]);
    }
}
