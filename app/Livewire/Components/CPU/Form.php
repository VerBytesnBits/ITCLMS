<?php

namespace App\Livewire\Components\CPU;

use Livewire\Component;
use App\Models\CPU;
use App\Models\SystemUnit;
use App\Livewire\Components\Traits\PartTrait;

class Form extends Component
{
    use PartTrait;

    protected $listeners = ['open-cpu-form' => 'openForm'];

    public function render()
    {
        return view('livewire.components.cpu.form', [
            'units' => SystemUnit::all()
        ]);
    }
    protected function getModelClass()
    {
        return CPU::class;
    }

    public function save()
    {
        $this->savePart(CPU::class);
    }
}
