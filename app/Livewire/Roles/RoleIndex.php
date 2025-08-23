<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
#[Layout('components.layouts.app', ['title' => 'Roles'])]
class RoleIndex extends Component
{
    #[Url(as: 'modal')]
    public ?string $modal = null;

    #[Url(as: 'id')]
    public ?int $id = null;

    public $roles;

    public function mount()
    {
        $this->refreshRoles();
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

    #[On('roleCreated')]
    #[On('roleUpdated')]
    #[On('roleDeleted')]
    public function refreshRoles()
    {
        $this->roles = Role::get();
    }

    public function deleteUser($id)
    {
        Role::findOrFail($id)->delete();
        $this->dispatch('swal', toast: true, icon: 'success', title: 'Deleted successfully', timer: 3000);
        $this->dispatch('roleDeleted');
    }

    public function render()
    {
        return view('livewire.roles.role-index');
    }
}
