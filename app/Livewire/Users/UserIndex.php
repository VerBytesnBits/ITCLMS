<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app', ['title' => 'Users'])]
class UserIndex extends Component
{
    public ?string $modal = null;          // which modal is open
    public ?int $selectedUserId = null;    // user id for modal

    // Open Assign Role modal
    public function openAssignRoleModal(int $userId): void
    {
        $this->selectedUserId = $userId;
        $this->modal = 'assign-role';
    }
    public function openCreateModal(): void
    {
        $this->selectedUserId = null;
        $this->modal = 'create';
    }

    // Open Edit User modal
    public function openEditModal(int $userId): void
    {
        $this->selectedUserId = $userId;
        $this->modal = 'edit';
    }
    // Close modal
    #[\Livewire\Attributes\On('closeModal')]
    public function closeModal(): void
    {
        $this->modal = null;
        $this->selectedUserId = null;
    }

    // // Handle role assigned from child modal
    // public function roleAssigned(): void
    // {
    //     $this->closeModal();
    //     $this->dispatch('refreshUsers'); // optional, refresh table
    // }
    #[\Livewire\Attributes\On('userCreated')]
    #[\Livewire\Attributes\On('userUpdated')]
    #[\Livewire\Attributes\On('roleAssigned')]
    public function refreshTable(): void
    {
        $this->closeModal(); // close modal after action
    }
    public function render()
    {
        return view('livewire.users.user-index', [
            'users' => User::with('roles')->get(),
        ]);
    }
}
