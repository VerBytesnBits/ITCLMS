<?php

namespace App\Livewire;

use Livewire\Component;

class LineChart extends Component
{
    public array $labels = [];
    public array $values = [];
    public string $title = 'Reports per Academic Year';

    public function mount(): void
    {
        // Sample static data
        $this->labels = ['2021-2022', '2022-2023', '2023-2024', '2024-2025'];
        $this->values = [120, 150, 180, 210];
    }

    public function render()
    {
        return view('livewire.line-chart');
    }
}
