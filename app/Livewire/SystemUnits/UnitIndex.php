<?php

namespace App\Livewire\SystemUnits;

use App\Livewire\UnitTable;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SystemUnit;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Lazy;
use App\Traits\SystemUnitQueryTrait;
use App\Models\Room;

#[Lazy]
#[Layout('components.layouts.app', ['title' => 'Units'])]
class UnitIndex extends Component
{
    use WithPagination, SystemUnitQueryTrait;

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


    public function placeholder()
    {
        return view('components.skeletons.units');
    }

    public function mount()
    {
        $this->rooms = Room::orderBy('name')->get();

    }

    public function updatedSelectedRoom()
    {
        $this->resetPage();

    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }


    public function updateUnitStatuses()
    {
        $units = SystemUnit::with(['components', 'peripherals'])->get();

        foreach ($units as $unit) {

            $unit->save();
        }

    }


    #[On('unit-deleted')]
    #[On('unit-saved')]
    #[On('issue-reported')]
    #[On('unit-restored')]
    public function refreshUnits()
    {
        $this->resetPage();
        $this->updateUnitStatuses();

        $this->dispatch('refresh-part-table')
            ->to(UnitTable::class);
            
    }


    public function create()
    {
        $this->selectedUnit = null;
        $this->modalMode = 'create';
        $this->showModal = true;
    }
    #[On('open-view-modal')]
    public function view($id)
    {
        $this->unitId = $id;
        $this->modalMode = 'view';
    }
    #[On('open-edit-modal')]
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
        return view('livewire.system-units.unit-index');
    }

}
