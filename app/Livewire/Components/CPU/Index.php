<?php

namespace App\Livewire\Components\CPU;

use Livewire\Component;
use App\Models\CPU;
use App\Livewire\Components\Traits\PartTrait;


class Index extends Component
{
    use PartTrait;

    protected $listeners = ['cpus-saved' => 'refreshItems'];

    public function mount()
    {
        $this->loadItems(CPU::class);
    }
    public function edit($id)
    {
        $this->dispatch('open-cpu-form', id: $id); // open modal form
    }

    public function delete($id)
    {
        $this->deleteItem(CPU::class, $id);
    }
    // Wrapper method for listener
    public function refreshItems()
    {
        $this->loadItems(CPU::class);
    }

    public function render()
    {
        return view('livewire.components.cpu.index');
    }
}
