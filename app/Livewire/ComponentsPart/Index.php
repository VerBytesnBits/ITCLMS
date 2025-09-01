<?php

namespace App\Livewire\ComponentsPart;

use Livewire\Component;
use App\Models\ComponentParts;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;

class Index extends Component
{
    #[Url(as: 'modal')]
    public ?string $modal = null;

    #[Url(as: 'id')]
    public ?int $id = null;

    public $components;

    public function mount()
    {
        $this->refreshComponents();
    }

    public function openCreateModal()
    {
        $this->id = null;
        $this->modal = 'create';
    }

    public function openEditModal($id)
    {
        $this->id = $id;
        $this->modal = 'edit';
    }

    #[On('closeModal')]
    public function closeModal()
    {
        $this->modal = null;
        $this->id = null;
    }

    #[On('componentCreated')]
    #[On('componentUpdated')]
    #[On('componentDeleted')]
    public function refreshComponents()
    {
        $this->components = ComponentParts::with(['systemUnit', 'room'])->get();
    }

    public function deleteComponent($id)
    {
        ComponentParts::findOrFail($id)->delete();

        $this->dispatch('swal', toast: true, icon: 'success', title: 'Component deleted successfully', timer: 3000);
        $this->dispatch('componentDeleted');
    }

    public function render()
    {
        return view('livewire.components-part.index');
    }
}
