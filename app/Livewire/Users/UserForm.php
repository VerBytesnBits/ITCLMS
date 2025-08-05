<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use SweetAlert2\Laravel\Swal;

class UserForm extends Component
{
    public ?User $user = null;

    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = 'lab_technician'; // default role
    public $roles = [];



    public function mount($userId = null)
    {
        $this->roles = Role::where('guard_name', 'web')->pluck('name')->mapWithKeys(function ($name) {
            return [$name => ucwords(str_replace('_', ' ', $name))];
        })->toArray();

        if ($userId) {
            $this->user = User::findOrFail($userId);
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->role = $this->user->roles()->pluck('name')->first() ?? 'user';
        }
    }



    protected function rules()
    {
        return [
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user?->id)],
            'password' => ['nullable', 'min:6'],
            'role' => ['required', Rule::in(array_keys($this->roles))],

        ];
    }

    public function save()
    {
        try {
            $validated = $this->validate();

            if ($this->user) {
                $updateData = [
                    'name' => $this->name,
                    'email' => $this->email,
                ];

                if (!empty($this->password)) {
                    $updateData['password'] = bcrypt($this->password);
                }

                $this->user->update($updateData);
                $this->user->syncRoles([$this->role]);

                $this->dispatch('swal', toast: true, icon: 'success', title: 'User Updated successfully', timer: 3000);
                $this->dispatch('userUpdated');
            } else {
                $userData = [
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => bcrypt($this->password),
                ];

                $user = User::create($userData);
                $user->assignRole($this->role);

                $this->dispatch('swal', toast: true, icon: 'success', title: 'User Created successfully', timer: 3000);
                $this->dispatch('userCreated');
            }

            $this->dispatch('closeModal');
        } catch (ValidationException $e) {
            // Dispatch alert
            $errors = $e->validator->errors()->all();
            $this->dispatch('swal', toast: true, icon: 'error', title: implode(' ', $errors), timer: 3000);

            // **Manually add errors to the Laravel error bag for Blade**
            $this->setErrorBag($e->validator->errors());

            // Optionally, stop execution or return
            return;
        }
    }


    public function render()
    {
        return view('livewire.users.user-form');
    }
}
