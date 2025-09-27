<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;
use Livewire\Attributes\Layout;
#[Layout('components.layouts.auth')]
class MultiStepReset extends Component
{
    public $email;
    public $step = 1;
    public $user;

    // Verification selection
    public $selectedVerification; // '2fa' or 'security'
    // <-- Add this

    // For 2FA
    public $otp;

    // For Security Q&A
    public $securityAnswers = [];
    public $date_of_birth;


    // For password reset
    public $password;
    public $password_confirmation;

    public function submitEmail()
    {
        $this->validate(['email' => 'required|email|exists:users,email']);
        $this->user = User::where('email', $this->email)->first();
        $this->step = 2;
    }

    public function submitSecurity()
    {
        if ($this->selectedVerification === '2fa') {
            // ----------------------------
            // 2FA Verification
            // ----------------------------
            $this->validate([
                'otp' => 'required|digits:6',
            ]);

            $google2fa = app('pragmarx.google2fa');

            if (!$google2fa->verifyKey($this->user->google2fa_secret, $this->otp)) {
                $this->addError('otp', 'Invalid OTP code.');
                return;
            }

        } elseif ($this->selectedVerification === 'security') {
            // ----------------------------
            // Security Questions + Date of Birth
            // ----------------------------
            $this->validate([
                'date_of_birth' => 'required|date',
                'securityAnswers.*' => 'required|string',
            ]);

            // Fetch answers from DB using IDs as keys
            $storedAnswers = $this->user->securityAnswers->pluck('answer', 'id')->toArray();

            // Verify each answer
            foreach ($this->securityAnswers as $id => $answer) {
                $dbAnswer = $storedAnswers[$id] ?? null;

                if (!$dbAnswer || strtolower(trim($answer)) !== strtolower(trim($dbAnswer))) {
                    $this->addError('securityAnswers.' . $id, 'Answer is incorrect.');
                    return;
                }
            }

            // Verify date_of_birth
            if ($this->date_of_birth != \Carbon\Carbon::parse($this->user->date_of_birth)->format('Y-m-d')) {
                $this->addError('date_of_birth', 'Date of birth does not match.');
                return;
            }

        } else {
            $this->addError('selectedVerification', 'Please select a verification method.');
            return;
        }

        // Move to next step
        $this->step = 4;
    }


    public function resetPassword()
    {
        $this->validate(['password' => 'required|min:8|confirmed']);
        $this->user->update(['password' => Hash::make($this->password)]);
        session()->flash('success', 'Password successfully reset!');
        return redirect()->route('login');
    }
    public function submitVerificationOption()
    {
        if (!$this->selectedVerification) {
            $this->addError('selectedVerification', 'Please select an option.');
            return;
        }

        $this->step = 3; // Move to “Verify & Completed”
    }
    public function updatedSelectedVerification($value)
    {
        $this->resetValidation('selectedVerification');
    }

    public function render()
    {
        return view('livewire.auth.multi-step-reset');
    }
}
