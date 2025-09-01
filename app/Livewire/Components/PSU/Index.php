<?php

namespace App\Livewire\Components\PSU;

use Livewire\Component;
use App\Models\PSU;
use App\Livewire\Components\Traits\PartTrait;


class Index extends Component
{
    use PartTrait;

    protected $listeners = ['psus-saved' => 'refreshItems'];

    public function mount()
    {
        $this->loadItems(PSU::class);
    }
    public function edit($id)
    {
        $this->dispatch('open-psu-form', id: $id); // open modal form
    }

    public function delete($id)
    {
        $this->deleteItem(PSU::class, $id);
    }
    // Wrapper method for listener
    public function refreshItems()
    {
        $this->loadItems(PSU::class);
    }

    public function render()
    {
        return view('livewire.components.psu.index');
    }
}
