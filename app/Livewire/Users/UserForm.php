<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

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
            'password' => $this->user ? ['nullable', 'min:6', 'confirmed'] : ['required', 'min:6', 'confirmed'],
            'role' => ['required', Rule::in(array_keys($this->roles))],

        ];
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->user) {
            $this->user->update([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password ? bcrypt($this->password) : $this->user->password,
            ]);

            $this->user->syncRoles([$this->role]); // update role
            $this->dispatch('userUpdated');
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
            ]);

            $user->assignRole($this->role); // assign role
            $this->dispatch('userCreated');
        }

        $this->dispatch('closeModal');
    }

    public function render()
    {
        return view('livewire.users.user-form');
    }
}
