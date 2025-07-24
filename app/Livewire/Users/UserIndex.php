<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Url;

class UserIndex extends Component
{
    #[Url(as: 'modal')]
    public ?string $modal = null;
    #[Url(as: 'id')]
    public ?int $id = null;
    public $users;

    protected $listeners = [
        'closeModal' => 'closeModal',
        'userCreated' => 'refreshUsers',
        'userUpdated' => 'refreshUsers',
        'userDeleted' => 'refreshUsers'
    ];

    public function mount()
    {
        $this->refreshUsers();
    }

    public function openCreateModal()
    {
        $this->id = null;
        $this->modal = 'create';
    }

    public function openEditModal($id)
    {
        $this->id = $id;
        $this->modal = 'edit';
    }

    public function closeModal()
    {
        $this->modal = null;
        $this->id = null;
    }

    public function refreshUsers()
    {
        $this->users = User::latest()->get();
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        $this->dispatch('userDeleted');
    }

    public function render()
    {
        return view('livewire.users.user-index');
    }
}
