<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class UserCreate extends Component
{
    public $name, $email;

    // protected $rules = [
    //     'name' => 'required|min:2',
    //     'email' => 'required|email|unique:users,email',
    // ];

    protected $listeners = ['openUserCreateModal' => 'openModal'];

    public $showModal = true;


    public function closeModal()
    {
        $this->dispatch('closeModal');
        $this->redirect('/users', navigate: true); // Clean URL
    }

    // public function save()
    // {
    //     $this->validate();

    //     User::create([
    //         'name' => $this->name,
    //         'email' => $this->email,
    //         'password' => bcrypt('password'), // temporary
    //     ]);

    //     session()->flash('success', 'User created successfully.');

    //     $this->closeModal();
    // }

    public function render()
    {
        return view('livewire.users.user-create');
    }
}
