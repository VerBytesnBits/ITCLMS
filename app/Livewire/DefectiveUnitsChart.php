<?php

namespace App\Livewire;

use Livewire\Component;

class DefectiveUnitsChart extends Component
{
    public array $labels = [];
    public array $values = [];
    public string $title = 'Defective Units per Laboratory';

    public function mount(): void
    {
        // Sample static data (replace later with DB query)
        $this->labels = ['Lab 1', 'Lab 2', 'Lab 3'];
        $this->values = [4, 7, 2, 5];
    }

    public function render()
    {
        return view('livewire.defective-units-chart');
    }
}
