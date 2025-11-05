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

    public $selectedPeripherals = []; // [type => peripheral_id]
    public $selectedComponents = [];  // [part => component_id]

    protected $listeners = ['showAssignModal' => 'open'];


    public $partIcons = [
        //components
        'CPU' => 'images/icons/CPU.png',
        'RAM' => 'images/icons/ram.png',
        'PSU' => 'images/icons/PSU.png',
        'GPU' => 'images/icons/GPU.png',
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
    public function mount($unitId = null)
    {
        $this->unitId = $unitId;

        if ($this->unitId) {
            // Editing existing unit
            $this->unit = SystemUnit::with(['peripherals', 'components'])->findOrFail($unitId);

            foreach ($this->unit->peripherals as $p) {
                $this->selectedPeripherals[$p->type] = $p->id;
            }

            foreach ($this->unit->components as $c) {
                $this->selectedComponents[$c->part] = $c->id;
            }
        } else {
            // Creating new unit → initialize empty arrays
            $this->unit = new SystemUnit();
            $this->selectedPeripherals = [];
            $this->selectedComponents = [];
        }
    }




    public function updatedSearchPeripherals()
    {
        $this->resetPage();
    }
    public function updatedSearchComponents()
    {
        $this->resetPage();
    }

    // =========================
// Available Peripherals (Paginated + Search + Multi-field using whereAny)
// =========================
    public function getAvailablePeripheralsProperty()
    {
        $types = Peripheral::select('type')->distinct()->pluck('type');
        $available = [];

        foreach ($types as $type) {
            $query = Peripheral::query()
                ->where('type', $type)
                ->whereNull('system_unit_id')
                ->where('status', 'available')
                ->select('*');

            if ($this->searchPeripherals) {
                $search = "%{$this->searchPeripherals}%";
                $query->whereAny(['brand', 'model', 'type', 'serial_number'], 'like', $search);
            }

            $available[$type] = $query->paginate(5, ['*'], $type . '_page');
        }

        return $available;
    }

    // =========================
// Available Components (Paginated + Search + Multi-field using whereAny)
// =========================
    public function getAvailableComponentsProperty()
    {
        $parts = ComponentParts::select('part')->distinct()->pluck('part');
        $available = [];

        foreach ($parts as $part) {
            $query = ComponentParts::query()
                ->where('part', $part)
                ->whereNull('system_unit_id')
                ->where('status', 'available')
                ->select('*');

            if ($this->searchComponents) {
                $search = "%{$this->searchComponents}%";
                $query->whereAny(['brand', 'model', 'capacity', 'part', 'serial_number'], 'like', $search);
            }

            $available[$part] = $query->paginate(5, ['*'], $part . '_page');
        }

        return $available;
    }


    // =========================
// Peripheral Assign/Unassign
// =========================
    public function assignSelected($type, $peripheralId)
    {
        $peripheral = Peripheral::find($peripheralId);
        $unit = SystemUnit::find($this->unitId);

        if ($peripheral && $unit) {
            // Update peripheral assignment
            $peripheral->update([
                'system_unit_id' => $this->unitId,
                'status' => 'In Use',
            ]);

            $this->selectedPeripherals[$type] = $peripheral->id;

            // ✅ Log the assignment on the SystemUnit
            activity()
                ->causedBy(Auth::user())
                ->performedOn($unit)
                ->withProperties([
                    'type' => $type,
                    'peripheral_id' => $peripheral->id,
                    'peripheral_serial' => $peripheral->serial_number,
                    'unit_name' => $unit->name,
                ])
                ->log("Assigned peripheral ({$peripheral->serial_number}) to  '{$unit->name}'");

            // ✅ SweetAlert success
            $this->dispatch('swal', [
                'title' => 'Peripheral Assigned!',
                'text' => "{$peripheral->serial_number} successfully assigned to {$unit->name}.",
            ]);
        }
    }

    public function unassign($type)
    {
        $peripheralId = $this->selectedPeripherals[$type] ?? null;
        $unit = SystemUnit::find($this->unitId);

        if ($peripheralId && $unit) {
            $peripheral = Peripheral::find($peripheralId);
            if ($peripheral) {
                // Update peripheral unassignment
                $peripheral->update([
                    'system_unit_id' => null,
                    'status' => 'Available',
                ]);

                // ✅ Log the unassignment on the SystemUnit
                activity()
                    ->causedBy(Auth::user())
                    ->performedOn($unit) // Important: link to system unit
                    ->withProperties([
                        'type' => $type,
                        'peripheral_id' => $peripheral->id,
                        'peripheral_serial' => $peripheral->serial_number,
                        'unit_name' => $unit->name,
                    ])
                    ->log("Unassigned peripheral ({$peripheral->serial_number}) from  '{$unit->name}'");

                // ✅ SweetAlert success
                $this->dispatch('swal', [
                    'title' => 'Peripheral Unassigned!',
                    'text' => "{$peripheral->serial_number} has been unassigned from {$unit->name}.",
                ]);
            }

            $this->selectedPeripherals[$type] = null;
        }
    }


    // =========================
// Component Assign/Unassign
// =========================
    public function assignComponent($part, $componentId)
    {
        $component = ComponentParts::findOrFail($componentId);
        $unit = SystemUnit::findOrFail($this->unitId);

        if ($component && $unit) {
            $component->update([
                'system_unit_id' => $this->unitId,
                'room_id' => $unit->room_id,
                'status' => 'In Use',
            ]);

            $this->selectedComponents[$part] = $component->id;

            activity()
                ->causedBy(Auth::user())
                ->performedOn($unit) // ← link to the system unit
                ->withProperties([
                    'item' => $component->serial_number, // optional
                    'part' => $part,
                    'component_id' => $componentId,
                ])
                ->log("Assigned component ({$component->serial_number}) to  '{$unit->name}'");

            // ✅ SweetAlert success
            $this->dispatch('swal', [
                'title' => 'Component Assigned!',
                'text' => "{$component->serial_number} successfully assigned to {$unit->name}.",
            ]);
        }
    }

    public function unassignComponent($part)
    {
        $componentId = $this->selectedComponents[$part] ?? null;
        $unit = SystemUnit::find($this->unitId);

        if ($componentId && $unit) {
            $component = ComponentParts::findOrFail($componentId);

            if ($component) {
                $component->update([
                    'system_unit_id' => null,
                    'room_id' => null,
                    'status' => 'Available',
                ]);

                // ✅ Log the unassignment
                activity()
                    ->causedBy(Auth::user())
                    ->performedOn($unit)
                    ->withProperties([
                        'item' => $component->serial_number, // optional
                        'part' => $part,
                        'component_id' => $componentId,
                    ])
                    ->log("Unassigned component ({$component->serial_number}) from  '{$unit->name}'");

                // ✅ SweetAlert success
                $this->dispatch('swal', [
                    'title' => 'Component Unassigned!',
                    'text' => "{$component->serial_number} has been unassigned from {$unit->name}.",
                ]);
            }

            $this->selectedComponents[$part] = null;
        }
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
