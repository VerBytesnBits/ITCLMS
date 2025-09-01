<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;
use App\Models\Peripheral;

class UnitAssignPeripherals extends Component
{
    public $unitId;
    public $unit;
    public $peripheralsByType = [];
    public $selectedPeripherals = [];

    protected $listeners = ['showModal' => 'open'];

    public function mount($unitId)
    {
        $this->unitId = $unitId;
        $this->unit = SystemUnit::find($unitId);

        // Initialize available types
        $types = Peripheral::select('type')->distinct()->pluck('type')->toArray();
        foreach ($types as $type) {
            $this->peripheralsByType[$type] = Peripheral::where('type', $type)
                ->whereNull('system_unit_id')
                ->get();

            $this->selectedPeripherals[$type] = null;
        }
    }

    public function assign()
    {
        foreach ($this->selectedPeripherals as $type => $peripheralId) {
            if ($peripheralId) {
                $peripheral = Peripheral::find($peripheralId);
                if ($peripheral) {
                    $peripheral->system_unit_id = $this->unitId;
                    $peripheral->save();
                }
            }
        }

        $this->dispatch('closeModal');          // Close modal in parent
        $this->dispatch('peripheralsAssigned'); // Refresh UnitIndex
        $this->dispatch('swal', toast: true, icon: 'success', title: 'Peripherals assigned!', timer: 3000);
    }

    public function render()
    {
        return view('livewire.system-units.unit-assign-peripherals');
    }
}
