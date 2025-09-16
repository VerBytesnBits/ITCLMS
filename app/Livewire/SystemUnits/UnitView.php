<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;
use Livewire\Attributes\On;
class UnitView extends Component
{
    public $unitId;
    public $unit;
   
    public function mount($unitId)
    {
        $this->unitId = $unitId;
        $this->unit = SystemUnit::findOrFail($unitId);
    }
  
    public function render()
    {
        return view('livewire.system-units.unit-view');
    }
}

