<?php

namespace App\Livewire\Components\RAM;

use Livewire\Component;
use App\Models\RAM;
use App\Models\SystemUnit;
use App\Livewire\Components\Traits\PartTrait;

class Form extends Component
{
    use PartTrait;

    protected $listeners = ['open-ram-form' => 'openForm'];

    public function render()
    {
        return view('livewire.components.ram.form', [
            'units' => SystemUnit::all()
        ]);
    }
    protected function getModelClass()
    {
        return RAM::class;
    }

    public function save()
    {
        $this->savePart(RAM::class);
    }
}
