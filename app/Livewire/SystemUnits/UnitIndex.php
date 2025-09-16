<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;

#[Layout('components.layouts.app', ['title' => 'Units'])]
class UnitIndex extends Component
{
    
    public $units = [];
    public $rooms = [];
    public $search = '';
    public $roomFilter = '';
    public $statusFilter = '';
    public $selectedUnit = null;


    public $showModal = false;      // create/edit
    #[Url(as: 'modal')]
    public ?string $modalMode = null;       // 'create' or 'edit'
    #[Url(as: 'id')]
    public ?int $unitId;
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
        $this->rooms = \App\Models\Room::orderBy('name')->get();
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


    public function getUnitsProperty()
    {
        return SystemUnit::query()
            ->with('room')
            ->when(
                $this->search,
                fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhereHas('room', fn($r) => $r->where('name', 'like', "%{$this->search}%"))
            )
            ->when(
                $this->roomFilter,
                fn($q) =>
                $q->where('room_id', $this->roomFilter)
            )
            ->when(
                $this->statusFilter,
                fn($q) =>
                $q->where('status', $this->statusFilter)
            )
            ->latest()
            ->get();
    }

    // Create/Edit modals
    public function create()
    {
        $this->selectedUnit = null;
        $this->modalMode = 'create';
        $this->showModal = true;
    }




    public function view($id)
    {
        $this->unitId = $id;
        $this->modalMode = 'view';

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
        $this->modalMode = null;
        $this->unitId = null;
    }

    // Assign peripherals modal
    public function openAssignModal($unitId)
    {
        $this->assignUnitId = $unitId;
        $this->showAssignModal = true;
        $this->modalMode = 'assign';
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
