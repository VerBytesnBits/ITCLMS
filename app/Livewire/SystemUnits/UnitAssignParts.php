<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;
use App\Models\Peripheral;
use App\Models\ComponentParts;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use function activity;

class UnitAssignParts extends Component
{
    use WithPagination;

   
    public $mode = '';
    protected $queryString = [];
    public $searchPeripherals = '';
    public $searchComponents = '';
    public $unitId;
    public $unit;

    public string $tab = 'peripherals';
    public ?string $selectedType = null;
    public ?string $selectedPart = null;

   
    public $selectedPeripherals = []; 
    public $selectedComponents = [];  

   
    public bool $showInlineForm = false;

    
    public ?string $inlineModelType = null; 

   
    public ?string $inlineSelectedPart = null;

   
    public $inline_brand;
    public $inline_model;
    public $inline_serial_number;
    public $inline_room_id;

    public $inline_capacity;
    public $inline_clock_speed;
    public $inline_size;

    
    public $inline_connection_type;

   
    public $tempComponents = [];
    public $tempPeripherals = [];

    protected $listeners = [
        'showAssignModal' => 'open'
    ];

   
    public $partIcons = [
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

        'Monitor' => 'images/icons/display.png',
        'Keyboard' => 'images/icons/keyboard.png',
        'Mouse' => 'images/icons/mouse.png',
        'Headset' => 'images/icons/headset.png',
        'Speaker' => 'images/icons/speaker.png',
        'Camera' => 'images/icons/camera.png',
    ];

    public $allPeripheralTypes = ['Monitor', 'Keyboard', 'Mouse', 'Headset', 'Speaker', 'UPS', 'AVR'];
    public $allComponentParts = ['CPU', 'Motherboard', 'RAM', 'GPU', 'PSU', 'Storage', 'Casing', 'Cooler'];

    
    public function mount($unitId = null)
    {
        $this->unitId = $unitId;

        if ($this->unitId) {
         
            $this->unit = SystemUnit::with(['peripherals', 'components'])->findOrFail($unitId);

            foreach ($this->unit->peripherals as $p) {
                $this->selectedPeripherals[$p->type] = $p->id;
            }
            foreach ($this->unit->components as $c) {
                $this->selectedComponents[$c->part] = $c->id;
            }
        } else {
            
            $this->unit = new SystemUnit();
            $this->selectedPeripherals = [];
            $this->selectedComponents = [];
        }
    }
    public function getAvailablePeripheralsProperty()
    {
        $available = [];
        $roomId = $this->unitId ? SystemUnit::find($this->unitId)?->room_id : null;


        foreach ($this->allPeripheralTypes as $type) {

            $query = Peripheral::query()
                ->where('type', $type)
                ->where('status', 'Available') 
                ->whereNull('system_unit_id'); 


           
            if ($roomId) {
                $query->where(function ($q) use ($roomId) {
                    $q->where('room_id', $roomId)
                        ->orWhereNull('room_id');
                });
            }

           
            if ($this->searchPeripherals) {
                $search = "%{$this->searchPeripherals}%";
                $query->whereAny(
                    ['brand', 'model', 'serial_number'],
                    'like',
                    $search
                );
            }

           
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
                ->where('status', 'Available') 
                ->whereNull('system_unit_id'); 

            
            if ($roomId) {
                $query->where(function ($q) use ($roomId) {
                    $q->where('room_id', $roomId)
                        ->orWhereNull('room_id');
                });
            }

           
            if ($this->searchComponents) {
                $search = "%{$this->searchComponents}%";
                $query->whereAny(
                    ['brand', 'model', 'serial_number'],
                    'like',
                    $search
                );
            }

            
            $available[$part] = $query->paginate(5, ['*'], $part . '_page');
        }

        return $available;
    }
   
    public function addInlineForm($model, $part)
    {
        $this->inlineModelType = $model;
        $this->inlineSelectedPart = $part;
        $this->showInlineForm = true;

       
        $this->reset([
            'inline_brand',
            'inline_model',
            'inline_serial_number',
            'inline_room_id',
            'inline_capacity',
            'inline_clock_speed',
            'inline_size',
            'inline_connection_type',
        ]);
    }

    public function saveInlineItem()
    {
       
        $this->validate([
            'inline_serial_number' => 'required|string|max:255',
            'inline_brand' => 'nullable|string|max:255',
            'inline_model' => 'nullable|string|max:255',
        ], [
            'inline_serial_number.required' => 'Serial number is required.',
        ]);

       
        $serial = trim($this->inline_serial_number);

        $existsInComponents = ComponentParts::where('serial_number', $serial)->exists();
        $existsInPeripherals = Peripheral::where('serial_number', $serial)->exists();

        $existsInTemp = collect($this->tempComponents)->contains('serial_number', $serial)
            || collect($this->tempPeripherals)->contains('serial_number', $serial);

        if ($existsInComponents || $existsInPeripherals || $existsInTemp) {
            throw ValidationException::withMessages([
                'inline_serial_number' => 'This serial number already exists.',
            ]);
        }
        if ($this->inlineModelType === 'component') {

            $item = [
                'part' => $this->inlineSelectedPart,  
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
                'type' => $this->inlineSelectedPart, 
                'brand' => $this->inline_brand,
                'model' => $this->inline_model,
                'serial_number' => $this->inline_serial_number,
                'room_id' => $this->inline_room_id,
                'connection_type' => $this->inline_connection_type,
            ];

            $this->tempPeripherals[] = $item;
            $this->dispatch('tempPeripheralAdded', $item);

           
            $this->dispatch('peripheralTypeSelected', $this->inlineSelectedPart);
        }

       
        $this->dispatch('swal', [
            'toast' => true,
            'icon' => 'success',
            'title' => "{$this->inlineSelectedPart} added successfully",
            'timer' => 4000,
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
