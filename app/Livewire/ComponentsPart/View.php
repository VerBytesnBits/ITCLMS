<?php

namespace App\Livewire\ComponentsPart;

use Livewire\Component;
use App\Models\ComponentParts;

class View extends Component
{
    public $component;
    
    public function mount($id)
    {
        $this->component = ComponentParts::with(['systemUnit', 'room'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.components-part.view');
    }
}
