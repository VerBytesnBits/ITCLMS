<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

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
        try {
            $validated = $this->validate();

            if ($this->role) {
                // Update existing role
                $this->role->update(['name' => $this->roleName]);

                // Sync permissions
                $this->role->syncPermissions($this->permissions);

                // Show success alert
                $this->dispatch('swal', toast: true, icon: 'success', title: 'Role Updated successfully', timer: 3000);
                // Emit event
                $this->dispatch('roleUpdated');
            } else {
                // Create new role
                $role = Role::create(['name' => $this->roleName]);

                // Assign permissions
                $role->syncPermissions($this->permissions);

                // Show success alert
                $this->dispatch('swal', toast: true, icon: 'success', title: 'Role Created successfully', timer: 3000);
                // Emit event
                $this->dispatch('roleCreated');
            }

            // Close modal or do other post-save actions
            $this->dispatch('closeModal');
        } catch (ValidationException $e) {
            // Manually set errors for Blade validation display
            $this->setErrorBag($e->validator->errors());

            // Optional: Show error alert
            $errors = $e->validator->errors()->all();
            $this->dispatch('swal', toast: true, icon: 'error', title: implode(' ', $errors), timer: 3000);
            return;
        }
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
