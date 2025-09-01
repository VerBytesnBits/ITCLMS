<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class UnitIndex extends Component
{
    public $units = [];
    public $selectedUnit = null;

    // Modal controls
    public $showModal = false;      // create/edit
    public $modalMode = null;       // 'create' or 'edit'
    public $showAssignModal = false; // assign peripheral
    public $assignUnitId = null;     // unit id for assign modal

    protected $listeners = [
        'unit-saved' => 'loadUnits',
        'unit-deleted' => 'loadUnits',
        'closeModal' => 'closeModal',
        'closeAssignModal' => 'closeAssignModal',
    ];

    public function mount()
    {
        $this->loadUnits();
    }

    public function loadUnits()
    {
        $user = Auth::user();

        if ($user->hasRole('chairman')) {
            $this->units = SystemUnit::with('room')->orderBy('id', 'asc')->get();
        } else {
            $roomIds = $user->rooms->pluck('id');
            $this->units = SystemUnit::with('room')
                ->whereIn('room_id', $roomIds)
                ->orderBy('id', 'asc')
                ->get();
        }
    }

    // Create/Edit modals
    public function create()
    {
        $this->selectedUnit = null;
        $this->modalMode = 'create';
        $this->showModal = true;
    }

    public function edit(SystemUnit $unit)
    {
        $this->selectedUnit = $unit;
        $this->modalMode = 'edit';
        $this->showModal = true;
    }

    public function delete(SystemUnit $unit)
    {
        $unit->delete();
        $this->dispatch('unit-deleted');
    }

    #[On('closeModal')]
    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedUnit = null;
    }

    // Assign peripherals modal
    public function openAssignModal($unitId)
    {
        $this->assignUnitId = $unitId;
        $this->showAssignModal = true;
    }

    #[On('closeAssignModal')]
    public function closeAssignModal()
    {
        $this->showAssignModal = false;
        $this->assignUnitId = null;
    }

    public function render()
    {
        return view('livewire.system-units.unit-index');
    }
}
