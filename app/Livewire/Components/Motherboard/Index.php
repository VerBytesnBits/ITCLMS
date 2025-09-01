<?php

namespace App\Livewire\Components\Motherboard;

use Livewire\Component;
use App\Models\Motherboard;
use App\Livewire\Components\Traits\PartTrait;


class Index extends Component
{
    use PartTrait;

    protected $listeners = ['motherboards-saved' => 'refreshItems'];

    public function mount()
    {
        $this->loadItems(Motherboard::class);
    }
    public function edit($id)
    {
        $this->dispatch('open-motherboard-form', id: $id); // open modal form
    }

    public function delete($id)
    {
        $this->deleteItem(Motherboard::class, $id);
    }
    // Wrapper method for listener
    public function refreshItems()
    {
        $this->loadItems(Motherboard::class);
    }

    public function render()
    {
        return view('livewire.components.motherboard.index');
    }
}
