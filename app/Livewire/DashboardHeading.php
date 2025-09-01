<?php

namespace App\Livewire;

use Livewire\Component;

class DashboardHeading extends Component
{
    public $title;
    public $subtitle;
    public $icon;
    public $gradientFromColor = '#3b82f6'; // default blue
    public $gradientToColor   = '#7c3aed'; // default purple
    public $iconColor = 'text-blue-500';    // Tailwind color class for icon

    public function render()
    {
        return view('livewire.dashboard-heading');
    }
}
