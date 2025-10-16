<?php

namespace App\Livewire\Settings;

use Auth;
use Livewire\Component;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorAuthentication extends Component
{
    public $user, $urlQRCode, $secret, $otp;

    public function mount()
    {
        $this->user = auth()->user();
        $google2fa = app('pragmarx.google2fa');

        if ($this->user->google2fa_secret) {
            // Reuse saved secret
            $this->secret = $this->user->google2fa_secret;
        } else {
            // Generate once
            $this->secret = $google2fa->generateSecretKey();
            session(['2fa_secret' => $this->secret]);
        }

        $this->urlQRCode = $google2fa->getQRCodeInline(
            config("app.name"),
            $this->user->email,
            $this->secret
        );
    }

    public function verifyOTP()
    {
        $this->validate([
            "otp" => "required"
        ]);

        $google2fa = app('pragmarx.google2fa');

        // Use session if user doesn’t already have a secret
        $secret = $this->user->google2fa_secret ?? session('2fa_secret');

        $valid = $google2fa->verifyKey($secret, $this->otp);

        if (!$valid) {
            $this->addError('otp', 'Invalid OTP code.');
            return;
        }

        //  Toggle enable/disable
        if ($this->user->google2fa_enabled) {
            // If enabled, disable it (but keep secret)
            $this->user->update([
                'google2fa_enabled' => false,
            ]);

            // session()->forget('2fa_passed');

            return redirect()->route('settings.2fa')->with('success', '2FA disabled successfully.');
        } else {
            // If disabled (or first setup), enable it
            $this->user->update([
                'google2fa_secret' => $this->secret,
                'google2fa_enabled' => true,
            ]);

            session(['2fa_passed' => true]);
            session(['google2fa' => true]);

            return redirect()->route('settings.2fa')->with('success', '2FA enabled successfully.');
        }
    }

    public function render()
    {
        return view('livewire.settings.two-factor-authentication');
    }
}
