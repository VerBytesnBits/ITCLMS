<?php

namespace App\Livewire\Components\RAM;

use Livewire\Component;
use App\Models\RAM;
use App\Livewire\Components\Traits\PartTrait;


class Index extends Component
{
    use PartTrait;

    protected $listeners = ['rams-saved' => 'refreshItems'];

    public function mount()
    {
        $this->loadItems(RAM::class);
    }
    public function edit($id)
    {
        $this->dispatch('open-ram-form', id: $id); // open modal form
    }

    public function delete($id)
    {
        $this->deleteItem(RAM::class, $id);
    }
    // Wrapper method for listener
    public function refreshItems()
    {
        $this->loadItems(RAM::class);
    }

    public function render()
    {
        return view('livewire.components.ram.index');
    }
}
