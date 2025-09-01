<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;

class UserIndex extends Component
{
    public function render()
    {
        return view('livewire.users.user-index', [
            'users' => User::with('roles')->get(),
        ]);
    }
}
