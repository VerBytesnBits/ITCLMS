<?php

namespace App\Livewire\Components;

use Livewire\Component;

class ComponentsIndex extends Component
{
    public $tab = 'cpu'; // default tab

    
    protected $queryString = [
        'tab' => ['except' => 'cpu'], // "cpu" won’t be shown in URL
    ];

    public function setTab($tab)
    {
        $this->tab = $tab;
    }

    public function render()
    {
        return view('livewire.components.components-index');
    }
}
