<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Permission;

class RoleCreate extends Component
{
    public $name;
    public  $permissions = [];
    public  $allPermissions = [];
    protected $listeners = ['openUserCreateModal' => 'openModal'];

    public $showModal = true;


    public function closeModal()
    {
        $this->dispatch('closeModal');
        $this->redirect('/roles', navigate: true); // Clean URL
    }
    public function mount()
    {
        // $this->$allPermissions = Permission::get();
    }
    public function render()
    {
        return view('livewire.roles.role-create');
    }
}
