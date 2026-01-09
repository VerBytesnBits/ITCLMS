<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignRole extends Component
{
    public ?int $userId = null;
    public ?string $selectedRole = null;
    public array $roles = [];

    public function mount($userId)
    {
        $this->userId = $userId;

        $this->roles = Role::pluck('name')->toArray();
        $user = User::find($this->userId);
        $this->selectedRole = $user?->roles->first()?->name;
    }

    public function assignRole()
    {
        $this->validate([
            'selectedRole' => 'required|string|exists:roles,name',
        ]);

        $user = User::find($this->userId);
        if ($user) {
            $user->syncRoles([$this->selectedRole]); 
            $this->dispatch('swal:toast', [
                'title' => 'Role assigned successfully!',
                'icon' => 'success',
            ]);
            $this->dispatch('roleAssigned'); 
            $this->reset();
        }
    }

    public function render()
    {
        return view('livewire.users.assign-role');
    }
}
