<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SystemUnit;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $totalUnits = 0;
    public $operationalUnits = 0;
    public $nonOperationalUnits = 0;

    public $showModal = false;
    public $modalType = null;

    public function mount()
    {
        $user = Auth::user();

        $query = SystemUnit::query();

        // If Lab In-Charge → only fetch units they manage
        if ($user->hasRole('lab_incharge')) {
            $query->whereHas('room', fn($q) =>
                $q->where('lab_in_charge_id', $user->id)
            );
        }

        $this->totalUnits = (clone $query)->count();

        $this->operationalUnits = (clone $query)
            ->where('status', 'operational')
            ->count();

        // Non-Operational = everything that's not operational
        $this->nonOperationalUnits = (clone $query)
            ->where('status', '!=', 'operational')
            ->count();
    }

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
