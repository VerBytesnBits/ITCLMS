<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;
use App\Models\Peripheral;
use App\Models\ComponentParts;

class UnitAssignParts extends Component
{
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


    // =========================
    // Available Peripherals
    // =========================
    public function getAvailablePeripheralsProperty()
    {
        $types = Peripheral::select('type')->distinct()->pluck('type');
        $available = [];

        foreach ($types as $type) {
            $available[$type] = Peripheral::where('type', $type)
                ->whereNull('system_unit_id')
                ->where('status', 'available')
                ->get();
        }

        return $available;
    }

    // =========================
    // Available Components
    // =========================
    public function getAvailableComponentsProperty()
    {
        $parts = ComponentParts::select('part')->distinct()->pluck('part');
        $available = [];

        foreach ($parts as $part) {
            $available[$part] = ComponentParts::where('part', $part)
                ->whereNull('system_unit_id')
                ->where('status', 'available')
                ->get();
        }

        return $available;
    }

    // =========================
    // Peripheral Assign/Unassign
    // =========================
    public function assignSelected($type, $peripheralId)
    {
        $peripheral = Peripheral::find($peripheralId);
        if ($peripheral) {
            $peripheral->system_unit_id = $this->unitId;
            $peripheral->status = 'In Use';
            $peripheral->save();

            $this->selectedPeripherals[$type] = $peripheral->id;
        }
    }

    public function unassign($type)
    {
        $peripheralId = $this->selectedPeripherals[$type] ?? null;
        if ($peripheralId) {
            $peripheral = Peripheral::find($peripheralId);
            if ($peripheral) {
                $peripheral->system_unit_id = null;
                $peripheral->status = 'available';
                $peripheral->save();
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
        $unit = SystemUnit::findOrFail($this->unitId); // get the unit

        if ($component && $unit) {
            $component->system_unit_id = $this->unitId;
            $component->room_id = $unit->room_id; // inherit the room from the unit
            $component->status = 'In Use';
            $component->save();

            $this->selectedComponents[$part] = $component->id;
        }
    }


    public function unassignComponent($part)
    {
        $componentId = $this->selectedComponents[$part] ?? null;

        if ($componentId) {
            $component = ComponentParts::findOrFail($componentId);

            if ($component) {
                $component->system_unit_id = null;
                $component->room_id = null;
                $component->status = 'Available';
                $component->save();
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
