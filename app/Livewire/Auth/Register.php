<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\UserSecurityAnswer;

#[Layout('components.layouts.auth')]
class Register extends Component
{
    public string $name;

    public string $email;

    public string $password;

    public string $password_confirmation;
    public $date_of_birth;
    public $selectedQuestion; // array of selected questions
    public $answer;   // answers keyed by question
    public $step = 1;
    public $agreed = false; // default unchecked

    public function nextStep()
    {
        if ($this->step === 1) {
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
            ], [
                'name.required' => 'Please enter your full name.',
                'email.required' => 'Your email address is required.',
                'email.email' => 'Please provide a valid email address.',
                'email.unique' => 'This email is already registered.',
            ]);
        }

        if ($this->step === 2) {
            $this->validate([
                'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            ], [
                'password.required' => 'Please enter a password.',
                'password.confirmed' => 'Your password confirmation does not match.',
            ]);
        }

        if ($this->step < 3) {
            $this->step++;
        }
    }
    public function previousStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }



    public $availableQuestions = [
        "What is your mother's maiden name?",
        "What was the name of your first pet?",
        "What was your first school?",
        "What city were you born in?"
    ];

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
                'date_of_birth' => 'required|date',
                'selectedQuestion' => 'required|string',
                'answer' => 'required|string',
                'agreed' => 'accepted'
            ],
            [

                'name.required' => 'Please enter your full name.',
                'email.required' => 'Your email address is required.',
                'email.email' => 'Please provide a valid email address.',
                'email.unique' => 'This email is already registered.',
                'password.required' => 'Please enter a password.',
                'password.confirmed' => 'Your password confirmation does not match.',
                'date_of_birth.required' => 'Please provide your date of birth.',
                'selectedQuestion.required' => 'Please select a security question.',
                'answer.required' => 'Please enter an answer for your security question.',
                'agreed.accepted' => 'You must agree to the Terms & Conditions before continuing.',
            ]
        );




        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'date_of_birth' => $this->date_of_birth,
            ])

        ));

        UserSecurityAnswer::create([
            'user_id' => $user->id,
            'question' => $this->selectedQuestion,
            'answer' => $this->answer
        ]);

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}