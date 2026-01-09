<?php

namespace App\Livewire\Users;

use App\Models\User;
use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Services\UserService;
use App\Livewire\UsersTable;


class FormModal extends Component
{
    public ?User $user = null;

    #[Validate('required|min:3')]
    public string $name = '';

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('nullable|min:6')]
    public string $password = '';

    public array $roles = [];
    public ?string $selectedRole = null;

    public bool $isView = false;
    public ?int $userId = null;

    
    #[On('open-user-modal')]
    public function userDetail(string $mode, int $user = null)
    {
        $this->isView = $mode === 'view';

        
        $this->roles = Role::where('guard_name', 'web')
            ->pluck('name')
            ->mapWithKeys(fn($name) => [$name => ucwords(str_replace('_', ' ', $name))])
            ->toArray();

        if ($mode === 'create') {
            $this->resetForm();
            $this->selectedRole = 'lab_technician';
            return;
        }

        $this->user = User::findOrFail($user);
        $this->userId = $user;

        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->selectedRole = $this->user->roles()->first()?->name;
    }

   

    public function save(UserService $userService)
    {
        $validated = $this->validate([
            'name' => 'required|min:3',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->userId),
            ],
            'password' => [$this->userId ? 'nullable' : 'required', 'min:6'],
            'selectedRole' => ['required', Rule::in(array_keys($this->roles))],
        ]);

        if ($this->userId) {
            $userService->updateUser($this->userId, [
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
                'role' => $this->selectedRole,
            ]);

            $message = 'User updated successfully!';
        } else {
            $userService->saveUser([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
                'role' => $this->selectedRole,
            ]);

            $message = 'User created successfully!';
        }

        $this->resetForm();

        $this->dispatch('flash', [
            'message' => $message,
            'type' => 'success',
        ]);



        $this->dispatch('refresh-user-table')
            ->to(UsersTable::class);



        Flux::modal('user-modal')->close();

    }

    
    protected function resetForm()
    {
        $this->reset([
            'user',
            'userId',
            'name',
            'email',
            'password',
            'selectedRole',
            'isView',
        ]);
    }

    public function render()
    {
        return view('livewire.users.form-modal');
    }
}
