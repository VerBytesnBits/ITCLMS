<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SystemUnit;
use App\Models\Activity; // Optional if you have logs or activities
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public int $totalSystemUnits = 0;
    public int $unitsUnderMaintenance = 0;
    public $recentActivities = [];

    public function mount()
    {
        $user = Auth::user();

        $this->totalSystemUnits = SystemUnit::count();
        $this->unitsUnderMaintenance = SystemUnit::where('status', 'Under Maintenance')->count();

        // If you have an Activity or Log model, show recent activities
        if (class_exists(Activity::class)) {
            $query = Activity::query()->latest();

            if ($user->hasRole('lab_incharge')) {
                $query->whereHas('systemUnit.room', function ($q) use ($user) {
                    $q->where('lab_in_charge_id', $user->id);
                });
            } elseif ($user->hasRole('lab_technician')) {
                $query->where('technician_id', $user->id);
            }

            $this->recentActivities = $query->limit(10)->get();
        }
    }

    public $showModal = false;
    public $modalType = null;

    public function openModal($type)
    {
        $this->modalType = $type;
        $this->showModal = true;
    }


    public function render()
    {
        return view('livewire.dashboard');
    }
}
