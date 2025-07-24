<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleForm extends Component
{
    public ?Role $role = null;

    public $roleId = null;
    public $roleName = '';
    public $permissions = [];
    public $allPermissions = [];

    public $showModal = false;

    public function mount($roleId = null)
    {
        $this->allPermissions = Permission::all();

        if ($roleId) {
            $this->role = Role::findOrFail($roleId);
            $this->roleId = $this->role->id;
            $this->roleName = $this->role->name;
            $this->permissions = $this->role->permissions()->pluck('name')->toArray();
        }

        $this->showModal = true;
    }

    protected function rules()
    {
        return [
            'roleName' => ['required', 'min:3', 'unique:roles,name,' . $this->roleId],
            'permissions' => ['required']
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->role) {
            $this->role->update(['name' => $this->roleName]);
            $this->role->syncPermissions($this->permissions);
            $this->dispatch('roleUpdated');
        } else {
            $role = Role::create(['name' => $this->roleName]);
            $role->syncPermissions($this->permissions);
            $this->dispatch('roleCreated');
        }

        $this->dispatch('closeModal');
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.roles.role-form');
    }
}
