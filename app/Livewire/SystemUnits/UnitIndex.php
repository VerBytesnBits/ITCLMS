<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;

class UnitIndex extends Component
{
    public $units;

    protected $listeners = ['unit-saved' => 'loadUnits'];

    public function loadUnits()
    {
        $this->units = SystemUnit::with('room')->orderBy('id', 'asc')->get();
    }

    public function boot()
    {
        $this->loadUnits();
    }

    public function render()
    {
        return view('livewire.system-units.unit-index');
    }
}
