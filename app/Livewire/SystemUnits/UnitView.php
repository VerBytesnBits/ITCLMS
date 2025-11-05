<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;
use Livewire\Attributes\On;
class UnitView extends Component
{
    public $unitId;
    public $unit;
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
    
    public function mount($unitId)
    {
        $this->unitId = $unitId;
        $this->unit = SystemUnit::findOrFail($unitId);
    }
  
    public function render()
    {
        return view('livewire.system-units.unit-view',['partIcons' => $this->partIcons,]);
    }
}

