<?php

namespace App\Livewire\Users;

use App\Models\User;
use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Livewire\UsersTable;

class UserIndex extends Component
{
    use WithPagination, WithoutUrlPagination;

    #[On('refresh-user-listing')]
    public function refresh()
    {
        $this->resetPage();
        $this->dispatch('$refresh');
        
    }



    #[On('delete-user')]
    public function deleteUser(int $id)
    {
        User::findOrFail($id)->delete();

        $this->dispatch('flash', [
            'message' => 'User deleted successfully!',
            'type' => 'success',
        ]);

        $this->dispatch('refresh-user-table')
            ->to(UsersTable::class);

        Flux::modal('delete-confirmation-modal')->close();
    }

    public function render()
    {
        return view('livewire.users.user-index', [
            'users' => User::with('roles')
                ->orderBy('id', 'DESC')
                ->paginate(5),
        ]);
    }
}


