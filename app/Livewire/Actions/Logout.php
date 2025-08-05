<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    public function __invoke()
    {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        // Set flash alert
        session()->flash('alert', [
            'type' => 'info',
            'message' => 'You have been logged out.',
        ]);

        return redirect()->route('login');
    }
}
