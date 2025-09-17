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

        $this->roles = Role::pluck('name')->toArray(); // Get all roles
        $user = User::find($this->userId);
        $this->selectedRole = $user?->roles->first()?->name; // pre-select existing role
    }

    public function assignRole()
    {
        $this->validate([
            'selectedRole' => 'required|string|exists:roles,name',
        ]);

        $user = User::find($this->userId);
        if ($user) {
            $user->syncRoles([$this->selectedRole]); // ensures 1 role only
            $this->dispatch('swal:toast', [
                'title' => 'Role assigned successfully!',
                'icon' => 'success',
            ]);
            $this->dispatch('roleAssigned'); // optional: refresh parent table
            $this->reset(); // close modal if using conditional rendering
        }
    }

    public function render()
    {
        return view('livewire.users.assign-role');
    }
}
