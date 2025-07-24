<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\Url;

class RoleIndex extends Component
{

    #[Url(as: 'modal')]
    public ?string $modal = null;
    #[Url(as: 'id')]
    public ?int $id = null;
    public $roles;

    protected $listeners = [
        'closeModal' => 'closeModal',
        'roleCreated' => 'refreshRoles',
        'roleUpdated' => 'refreshRoles',
        'roleDeleted' => 'refreshRoles'
    ];

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

    public function closeModal()
    {
        $this->modal = null;
        $this->id = null;
    }

    public function refreshRoles()
    {
        $this->roles = Role::get();
    }

    public function deleteUser($id)
    {
        Role::findOrFail($id)->delete();
        $this->dispatch('roleDeleted');
    }
    public function render()
    {
        return view('livewire.roles.role-index');
    }
}
