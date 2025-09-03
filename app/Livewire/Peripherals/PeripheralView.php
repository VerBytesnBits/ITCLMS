<?php

namespace App\Livewire\Peripherals;

use Livewire\Component;
use App\Models\Peripheral;

class PeripheralView extends Component
{
    public ?Peripheral $peripheral = null;

    public function mount($id)
    {
        $this->peripheral = Peripheral::with(['systemUnit', 'room'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.peripherals.peripheral-view');
    }
}
