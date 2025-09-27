<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;

use Livewire\Component;

use Livewire\Attributes\Layout;
#[Layout('components.layouts.auth')]
class TwoFactorAuthentication extends Component
{
    public $otp = [];

    public function verifyOtp()
    {
        // Make sure OTP is treated as an array
        $otp = implode('', (array) $this->otp);

        $this->validate([
            'otp' => 'required|digits:6',
        ]);

        $google2fa = app('pragmarx.google2fa');

        $valid = $google2fa->verifyKey(auth()->user()->google2fa_secret, $otp);

        if ($valid) {
            session(['google2fa' => true]);

            return redirect()
                ->route('dashboard')
                ->with('success', '2FA verified!');
        }

        $this->addError('otp', 'Invalid OTP code.');
    }


    public function render()
    {
        $user = Auth::user();

        // Block if 2FA not enabled
        if (!$user->google2fa_enabled) {
            return redirect()->route('dashboard');
        }

        // Block if already verified in this session
        if (session('google2fa')) {
            return redirect()->route('dashboard');
        }

        // Otherwise, show the Livewire view
        return view('livewire.auth.two-factor-authentication');
    }
}
