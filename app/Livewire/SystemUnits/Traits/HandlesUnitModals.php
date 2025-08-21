<?php

namespace App\Livewire\SystemUnits\Traits;

use App\Models\SystemUnit;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

trait HandlesUnitModals
{
    public ?int $selectedUnitId = null;
    #[On('openReportModalFromTable')]
    public function openReportModal($id)
    {
        $this->selectedUnitId = $id;
        $this->modal = 'report';
    }

    public function openCreateModal()
    {
        $this->reset(['id', 'name', 'status', 'room_id']);
        $this->modal = 'create';
    }
    #[On('openEditModalFromTable')]
    public function openEditModal($id)
    {
        $unit = SystemUnit::findOrFail($id);

        if (
            Auth::user()->hasRole('lab_incharge') &&
            $unit->room->lab_in_charge_id !== Auth::id()
        ) {
            abort(403, 'Unauthorized action.');
        }

        $this->id = $unit->id;
        $this->name = $unit->name;
        $this->status = $unit->status;
        $this->room_id = $unit->room_id;
        $this->modal = 'edit';
    }
    #[On('openViewModalFromTable')]
    public function openViewModal($id)
    {
        $this->viewUnit = SystemUnit::with(array_merge($this->unitRelations, ['room']))
            ->findOrFail($id);

        if (
            Auth::user()->hasRole('lab_incharge') &&
            $this->viewUnit->room->lab_in_charge_id !== Auth::id()
        ) {
            abort(403, 'Unauthorized action.');
        }

        $this->modal = 'view';
    }
    #[On('closeModal')]
    public function closeModal()
    {
        $this->modal = null;
        $this->reset(['id', 'name', 'status', 'room_id', 'viewUnit']);
    }
}
