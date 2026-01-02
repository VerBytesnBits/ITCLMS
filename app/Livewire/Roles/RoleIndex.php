<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;

class RoleIndex extends Component
{
    #[Url(as: 'modal')]
    public ?string $modal = null;

    #[Url(as: 'id')]
    public ?int $id = null;

    public $roles;
    public $deleteRoleId = null;


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

    public function confirmDeleteRole($id)
    {
        $this->deleteRoleId = $id;
        $this->dispatch('delete-role-confirm');
    }

   #[On('deleteRoleConfirmed')]
   public function deleteRole()
    {
        Role::findOrFail($this->deleteRoleId)->delete();

        $this->dispatch('roleDeleted');
       
    }


 


    public function render()
    {
        return view('livewire.roles.role-index');
    }
}
