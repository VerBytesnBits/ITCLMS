<?php

namespace App\Livewire\Components\PSU;

use Livewire\Component;
use App\Models\PSU;
use App\Models\SystemUnit;
use App\Livewire\Components\Traits\PartTrait;

class Form extends Component
{
    use PartTrait;

    protected $listeners = ['open-psu-form' => 'openForm'];

    public function render()
    {
        return view('livewire.components.psu.form', [
            'units' => SystemUnit::all()
        ]);
    }
    protected function getModelClass()
    {
        return PSU::class;
    }

    public function save()
    {
        $this->savePart(PSU::class);
    }
}
