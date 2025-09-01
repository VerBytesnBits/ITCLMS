<?php

namespace App\Livewire\Components\ComputerCase;

use Livewire\Component;
use App\Models\ComputerCase;
use App\Models\SystemUnit;
use App\Livewire\Components\Traits\PartTrait;

class Form extends Component
{
    use PartTrait;

    protected $listeners = ['open-case-form' => 'openForm'];

    public function render()
    {
        return view('livewire.components.computer-case.form', [
            'units' => SystemUnit::all()
        ]);
    }
    protected function getModelClass()
    {
        return ComputerCase::class;
    }

    public function save()
    {
        $this->savePart(ComputerCase::class);
    }
}
