<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Activitylog\Models\Activity;


class ActivityBell extends Component
{
    public $activities = [];
    public $unreadCount = 0;

    public function mount()
    {
        $this->loadActivities();
    }

    public function loadActivities()
    {
      
        $this->activities = Activity::latest()->take(10)->get();
        $this->unreadCount = Activity::whereNull('read_at')->count();
    }

    public function markAllAsRead()
    {
        Activity::whereNull('read_at')->update(['read_at' => now()]);
        $this->unreadCount = 0;
        $this->loadActivities();
    }

    public function render()
    {
        return view('livewire.activity-bell');
    }
}

