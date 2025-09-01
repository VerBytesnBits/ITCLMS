<?php

namespace App\Livewire\Components\Drive;

use Livewire\Component;
use App\Models\Drive;
use App\Livewire\Components\Traits\PartTrait;


class Index extends Component
{
    use PartTrait;

    protected $listeners = ['drives-saved' => 'refreshItems'];

    public function mount()
    {
        $this->loadItems(Drive::class);
    }
    public function edit($id)
    {
        $this->dispatch('open-drive-form', id: $id); // open modal form
    }

    public function delete($id)
    {
        $this->deleteItem(Drive::class, $id);
    }
    // Wrapper method for listener
    public function refreshItems()
    {
        $this->loadItems(Drive::class);
    }

    public function render()
    {
        return view('livewire.components.drive.index');
    }
}
