<?php

namespace App\Livewire\Peripherals;

use Livewire\Component;
use App\Models\Peripheral;

class PeripheralAssign extends Component
{
    public $show = false;
    public $unitId;
    public $unit;
    public $selectedPeripherals = [];

    protected $listeners = ['openAssignPeripheralModal' => 'open'];

    public function open($unitId)
    {
        $this->unitId = $unitId;
        $this->show = true;
    }

    public function assign()
    {
        foreach ($this->selectedPeripherals as $peripheralId) {
            $peripheral = Peripheral::find($peripheralId);
            if ($peripheral) {
                $peripheral->update(['system_unit_id' => $this->unitId]);
            }
        }

        $this->dispatch('peripheralAssigned');
        $this->show = false;
    }

    public function render()
    {
        $types = Peripheral::query()->select('type')->distinct()->pluck('type');

        $peripheralsByType = [];
        foreach ($types as $type) {
            $peripheralsByType[$type] = Peripheral::where('type', $type)
                ->whereNull('system_unit_id')
                ->get();
        }

        return view('livewire.peripherals.peripheral-assign', [
            'peripheralsByType' => $peripheralsByType,
        ]);
    }
}
