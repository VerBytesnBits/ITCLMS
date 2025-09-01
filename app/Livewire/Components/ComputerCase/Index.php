<?php

namespace App\Livewire\Components\ComputerCase;

use Livewire\Component;
use App\Models\ComputerCase;
use App\Livewire\Components\Traits\PartTrait;


class Index extends Component
{
    use PartTrait;

    protected $listeners = ['cases-saved' => 'refreshItems'];

    public function mount()
    {
        $this->loadItems(ComputerCase::class);
    }
    public function edit($id)
    {
        $this->dispatch('open-case-form', id: $id); // open modal form
    }

    public function delete($id)
    {
        $this->deleteItem(ComputerCase::class, $id);
    }
    // Wrapper method for listener
    public function refreshItems()
    {
        $this->loadItems(ComputerCase::class);
    }

    public function render()
    {
        return view('livewire.components.computer-case.index');
    }
}
