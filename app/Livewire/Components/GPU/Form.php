<?php

namespace App\Livewire\Components\GPU;

use Livewire\Component;
use App\Models\GPU;
use App\Models\SystemUnit;
use App\Livewire\Components\Traits\PartTrait;

class Form extends Component
{
    use PartTrait;

    protected $listeners = ['open-gpu-form' => 'openForm'];

    public function render()
    {
        return view('livewire.components.gpu.form', [
            'units' => SystemUnit::all()
        ]);
    }
    protected function getModelClass()
    {
        return GPU::class;
    }

    public function save()
    {
        $this->savePart(GPU::class);
    }
}
