<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Lazy;

#[Lazy]
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
    public $selectedRoom = null; // null = all rooms
    public $operationalCount = 0;
    public $nonOperationalCount = 0;

    public function placeholder()
    {
        return view('components.skeletons.units');
    }
    public function updatedSelectedRoom()
    {
        $this->updateUnitCounts();
        $this->loadUnits();
    }
    public function updatedStatusFilter()
    {
        $this->loadUnits();
    }
    protected function updateUnitCounts()
    {
        $user = Auth::user();
        $query = SystemUnit::query();

        if ($this->selectedRoom) {
            $query->where('room_id', $this->selectedRoom);
        }
        if (!$user->hasRole('chairman')) {
            $roomIds = $user->rooms->pluck('id');
            $query->whereIn('room_id', $roomIds);
        }
        $this->operationalCount = (clone $query)->where('status', 'Operational')->count();
        $this->nonOperationalCount = (clone $query)->where('status', 'Non-Operational')->count();
    }


    public function mount()
    {
        $this->rooms = \App\Models\Room::orderBy('name')->get();
        $this->loadUnits();

    }
    #[On(event: 'unit-deleted')]
    #[On(event: 'unit-saved')]
    public function loadUnits()
    {
        $user = Auth::user();

        $query = SystemUnit::with('room');

        if (!$user->hasRole('chairman')) {
            $roomIds = $user->rooms->pluck('id');
            $query->whereIn('room_id', $roomIds);
        }

        if ($this->selectedRoom) {
            $query->where('room_id', $this->selectedRoom);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $this->units = $query->orderBy('id', 'asc')->get();

        // Recalculate counts
        $this->updateUnitCounts();
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



    public function render()
    {
        return view('livewire.system-units.unit-index');
    }
}
