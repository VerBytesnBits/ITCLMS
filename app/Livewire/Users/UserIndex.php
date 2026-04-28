<?php

namespace App\Livewire\Users;

use App\Models\User;
use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Livewire\UsersTable;
use Masmerise\Toaster\Toaster;

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

        Toaster::success('User deleted successfully!');

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


