<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;


use Livewire\Attributes\Layout;
#[Layout('components.layouts.app', ['title' => 'Users'])]
class UserIndex extends Component
{
    #[Url(as: 'modal')]
    public ?string $modal = null;

    #[Url(as: 'id')]
    public ?int $id = null;

    public $users;




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

    #[On('closeModal')]
    public function closeModal()
    {
        $this->modal = null;
        $this->id = null;
    }

    #[On('userCreated')]
    #[On('userUpdated')]
    #[On('userDeleted')]
    public function refreshUsers()
    {
        $this->users = User::latest()->get();
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        $this->dispatch('swal', toast: true, icon: 'success', title: 'Deleted successfully', timer: 3000);
        $this->dispatch('userDeleted');
    }

    public function render()
    {
        return view('livewire.users.user-index');
           
    }
}
