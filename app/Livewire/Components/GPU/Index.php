<?php

namespace App\Livewire\Components\GPU;

use Livewire\Component;
use App\Models\GPU;
use App\Livewire\Components\Traits\PartTrait;


class Index extends Component
{
    use PartTrait;

    protected $listeners = ['gpus-saved' => 'refreshItems'];

    public function mount()
    {
        $this->loadItems(GPU::class);
    }
    public function edit($id)
    {
        $this->dispatch('open-gpu-form', id: $id); // open modal form
    }

    public function delete($id)
    {
        $this->deleteItem(GPU::class, $id);
    }
    // Wrapper method for listener
    public function refreshItems()
    {
        $this->loadItems(GPU::class);
    }

    public function render()
    {
        return view('livewire.components.gpu.index');
    }
}
