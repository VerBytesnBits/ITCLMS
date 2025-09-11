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
            'position' => 'top-end',
            'title' => 'You have been logged out.',
        ]);
        
        // Example flash alert with customization
        // session()->flash('alert', [
        //     'type' => 'success',              // success | error | warning | info | question
        //     'position' => 'top-end',          // top-end | top-start | bottom-end | bottom-start | center
        //     'title' => 'Logout successfully!',
        //     'text' => 'You have been logged out.', // optional subtitle
        //     'background' => '#1f2937',        // custom background (Tailwind gray-800)
        //     'color' => '#716add',             // text color (Tailwind gray-50)
        //     'iconHtml' => '🚀',               // emoji or custom HTML icon
        //     'confirmButton' => false,         // whether to show confirm button
        //     'timer' => 4000,                  // ms before auto-close
        // ]);


        return redirect()->route('login');
    }
}
