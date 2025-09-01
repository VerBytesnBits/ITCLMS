<?php

namespace App\Livewire\Components\Drive;

use Livewire\Component;
use App\Models\Drive;
use App\Models\SystemUnit;
use App\Livewire\Components\Traits\PartTrait;

class Form extends Component
{
    use PartTrait;

    protected $listeners = ['open-drive-form' => 'openForm'];

    public function render()
    {
        return view('livewire.components.drive.form', [
            'units' => SystemUnit::all()
        ]);
    }
    protected function getModelClass()
    {
        return Drive::class;
    }

    public function save()
    {
        $this->savePart(Drive::class);
    }
}
