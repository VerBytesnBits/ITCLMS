<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use Livewire\WithPagination;
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
    use WithPagination;

    public $rooms = [];
    public $search = '';
    public $selectedRoom = '';
    public $statusFilter = '';

    public $selectedUnit = null;
    public $showModal = false;
    #[Url(as: 'modal')]
    public ?string $modalMode = null;
    #[Url(as: 'id')]
    public ?int $unitId = null;

    public $showAssignModal = false;
    public $assignUnitId = null;

    public $operationalCount = 0;
    public $nonOperationalCount = 0;

    public function placeholder()
    {
        return view('components.skeletons.units');
    }

    public function mount()
    {
        $this->rooms = \App\Models\Room::orderBy('name')->get();
        $this->updateUnitCounts();
    }

    public function updatedSelectedRoom()
    {
        $this->resetPage();
        $this->updateUnitCounts();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    protected function updateUnitCounts()
    {
        $user = Auth::user();
        $query = SystemUnit::query()->with(['components', 'peripherals']); // eager load components/peripherals

        if ($this->selectedRoom) {
            $query->where('room_id', $this->selectedRoom);
        }

        if (!$user->hasAnyRole(['chairman', 'Tester'])) {
            $roomIds = $user->rooms->pluck('id');
            $query->whereIn('room_id', $roomIds);
        }

        // Dynamically update status of all units in this query
        $units = $query->get();
        foreach ($units as $unit) {
            $unit->checkOperationalStatus(); // updates status in DB if changed
        }

        // Use clone queries for counts as before
        $this->operationalCount = (clone $query)->where('status', 'Operational')->count();
        $this->nonOperationalCount = (clone $query)->where('status', 'Non-Operational')->count();
    }


    public function updateUnitStatuses()
    {
        $units = SystemUnit::with(['components', 'peripherals'])->get();

        foreach ($units as $unit) {
            $unit->status = $unit->checkOperationalStatus();
            $unit->save();
        }

        $this->updateUnitCounts(); // refresh counts
    }


    #[On('unit-deleted')]
    #[On('unit-saved')]
    #[On('issue-reported')]
    #[On('unit-restored')]
    public function refreshUnits()
    {
        $this->resetPage();
        $this->updateUnitCounts();
    }

    public function getUnitsProperty()
    {
        $user = Auth::user();

        // Eager load room, components, and peripherals
        $query = SystemUnit::with(['room', 'components', 'peripherals']);

        // Limit units to the user's assigned rooms if not chairman/tester
        if (!$user->hasAnyRole(['chairman', 'Tester'])) {
            $roomIds = $user->rooms->pluck('id');
            $query->whereIn('room_id', $roomIds);
        }

        // Apply room filter
        $query->when($this->selectedRoom, fn($q) => $q->where('room_id', $this->selectedRoom));

        // Apply search filter
        $query->when(
            $this->search,
            fn($q) =>
            $q->where('name', 'like', "%{$this->search}%")
                ->orWhereHas('room', fn($r) => $r->where('name', 'like', "%{$this->search}%"))
        );

        // Fetch paginated results
        $units = $query->orderBy('id', 'asc')->paginate(10);

        // Dynamically calculate operational status for each unit
        $units->getCollection()->transform(function ($unit) {
            $unit->status = $unit->checkOperationalStatus(); // method in SystemUnit model
            return $unit;
        });

        // Apply status filter after dynamic status calculation
        if ($this->statusFilter) {
            $filtered = $units->getCollection()->filter(fn($unit) => $unit->status === $this->statusFilter);
            $units->setCollection($filtered);
        }

        return $units;
    }


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
        $this->resetPage();
        $this->showModal = false;
        $this->selectedUnit = null;
        $this->modalMode = null;
        $this->unitId = null;
        $this->dispatch('clear-url-query');
    }


    public function render()
    {
        return view('livewire.system-units.unit-index', [
            'units' => $this->units,
        ]);
    }
}
