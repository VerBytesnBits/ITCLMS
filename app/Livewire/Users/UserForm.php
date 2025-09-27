<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class UserForm extends Component
{
    public ?User $user = null;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public array $roles = [];         
    public ?string $selectedRole = null; 

    public function mount($userId = null)
    {
        // Load all roles for dropdown
        $this->roles = Role::where('guard_name', 'web')
            ->pluck('name')
            ->mapWithKeys(fn($name) => [$name => ucwords(str_replace('_', ' ', $name))])
            ->toArray();

        if ($userId) {
            $this->user = User::findOrFail($userId);
            $this->name = $this->user->name ?? '';
            $this->email = $this->user->email ?? '';
            $this->selectedRole = $this->user->roles()->first()?->name;
        }

        if (!$userId) {
            $this->selectedRole = 'lab_technician'; // default role
        }
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user?->id)],
            'password' => [$this->user ? 'nullable' : 'required', 'min:6'],
            'selectedRole' => ['required', 'string', Rule::in(array_keys($this->roles))],
        ];
    }

    public function save()
    {
        try {
            $validated = $this->validate();

            // Chairmen don't need room assignment here — handled in Room module
            if ($this->selectedRole === 'chairman') {
                // Business logic: Chairman = global access (not stored per room)
            }

            if ($this->user) {
                $updateData = [
                    'name' => $this->name,
                    'email' => $this->email,
                ];

                if (!empty($this->password)) {
                    $updateData['password'] = bcrypt($this->password);
                }

                $this->user->update($updateData);
                $this->user->syncRoles([$this->selectedRole]);

                $this->dispatch('swal', toast: true, icon: 'success', title: 'User updated successfully', timer: 3000);
                $this->dispatch('userUpdated');
            } else {
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => bcrypt($this->password),
                ]);

                $user->assignRole([$this->selectedRole]);

                $this->dispatch('swal', toast: true, icon: 'success', title: 'User created successfully', timer: 3000);
                $this->dispatch('userCreated');
            }

            $this->dispatch('closeModal');

        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
            $errors = $e->validator->errors()->all();
            $this->dispatch('swal', toast: true, icon: 'error', title: implode(' ', $errors), timer: 3000);
        }
    }

    public function render()
    {
        return view('livewire.users.user-form');
    }
}
