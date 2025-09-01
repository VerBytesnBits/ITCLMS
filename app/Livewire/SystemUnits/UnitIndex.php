<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;
use Illuminate\Support\Facades\Auth;

class UnitIndex extends Component
{
    public $units;

    protected $listeners = ['unit-saved' => 'loadUnits'];

    public function loadUnits()
    {
        $user = Auth::user();

        if ($user->hasRole('chairman')) {
            // Chairman sees all units
            $this->units = SystemUnit::with('room')->orderBy('id', 'asc')->get();
        } else {
            // Otherwise only units from rooms assigned to this user
            $roomIds = $user->rooms->pluck('id'); // assumes User has rooms() relationship
            $this->units = SystemUnit::with('room')
                ->whereIn('room_id', $roomIds)
                ->orderBy('id', 'asc')
                ->get();
        }
    }

    public function boot()
    {
        $this->loadUnits();
    }

    public function render()
    {
        return view('livewire.system-units.unit-index');
    }
}
