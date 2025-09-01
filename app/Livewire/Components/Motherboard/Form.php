<?php

namespace App\Livewire\Components\Motherboard;

use Livewire\Component;
use App\Models\Motherboard;
use App\Models\SystemUnit;
use App\Livewire\Components\Traits\PartTrait;

class Form extends Component
{
    use PartTrait;

    protected $listeners = ['open-motherboard-form' => 'openForm'];

    public function render()
    {
        return view('livewire.components.motherboard.form', [
            'units' => SystemUnit::all()
        ]);
    }
    protected function getModelClass()
    {
        return Motherboard::class;
    }

    public function save()
    {
        $this->savePart(Motherboard::class);
    }
}
