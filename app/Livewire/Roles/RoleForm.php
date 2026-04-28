<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;
use Masmerise\Toaster\Toaster;
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
    private function normalizeRoleName($name)
    {
        // Convert camelCase → snake_case
        $name = preg_replace('/(?<!^)[A-Z]/', '_$0', $name);

        // Replace anything not letter/number with underscore
        $name = preg_replace('/[^a-zA-Z0-9]+/', '_', $name);

        // Lowercase + trim underscores
        return trim(strtolower($name), '_');
    }
    private function normalizeBase($name)
    {
        // Remove EVERYTHING except letters and numbers
        return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $name));
    }
    protected function rules()
    {
        return [
            'roleName' => [
                'required',
                'min:3',
                function ($attribute, $value, $fail) {
                    $inputBase = $this->normalizeBase($value);

                    $exists = Role::all()->contains(function ($role) use ($inputBase) {
                        return $this->normalizeBase($role->name) === $inputBase
                            && (!$this->roleId || $role->id != $this->roleId);
                    });

                    if ($exists) {
                        $fail('Role already exists.');
                    }
                }
            ],
            'permissions' => ['required']
        ];
    }


    public function save()
    {
        // Normalize FIRST
       

        // Validate AFTER normalization
        $this->validate();

        if ($this->role) {
            $this->role->update(['name' => $this->roleName]);
            $this->role->syncPermissions($this->permissions);

            Toaster::success('Role updated successfully!');
            $this->dispatch('roleUpdated');

        } else {
            $role = Role::create(['name' => $this->roleName]);
            $role->syncPermissions($this->permissions);

            Toaster::success('Role created successfully!');
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
