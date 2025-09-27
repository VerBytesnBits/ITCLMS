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
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';
    public $date_of_birth;
    public $selectedQuestion; // array of selected questions
    public $answer;   // answers keyed by question

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
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'date_of_birth' => 'required|date',
            'selectedQuestion' => 'required|string', // for security question
            'answer' => 'required|string'           // for security answer
        ]);




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