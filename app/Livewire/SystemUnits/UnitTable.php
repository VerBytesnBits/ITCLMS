<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;
use App\Support\PartsConfig;
use Illuminate\Support\Facades\Auth;
use App\Events\UnitUpdated;
use App\Events\UnitDeleted;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use App\Livewire\SystemUnits\Traits\HandlesUnitEcho;

class UnitTable extends Component
{

    public $units; // injected from parent (UnitIndex)
    #[On('units-updated')]
    public function reloadTable()
    {
        $this->dispatch('$refresh'); // forces rerender
    }

    public function openViewModal($id)
    {
        $this->dispatch('openViewModalFromTable', id: $id);
    }

    public function openEditModal($id)
    {
        $this->dispatch('openEditModalFromTable', id: $id);
    }

    public function openReportModal($id)
    {
        $this->dispatch('openReportModalFromTable', id: $id);
    }

    public function deleteUnit($id)
    {
        $unit = SystemUnit::with(PartsConfig::unitRelations())->findOrFail($id);

        if (
            Auth::user()->hasRole('lab_incharge') &&
            $unit->room->lab_in_charge_id !== Auth::id()
        ) {
            abort(403, 'Unauthorized action.');
        }

        // detach parts
        foreach (PartsConfig::unitRelations() as $relation) {
            $items = $unit->$relation;
            if ($items instanceof \Illuminate\Support\Collection) {
                foreach ($items as $item) {
                    $item->system_unit_id = null;
                    $item->save();
                }
            } elseif ($items) {
                $items->system_unit_id = null;
                $items->save();
            }
        }

        $unit->delete();
        event(new UnitDeleted($unit->id));
        broadcast(new UnitDeleted($unit->id))->toOthers();
        // broadcast(new UnitDeleted(['id' => $id]))->toOthers();


        session()->flash('success', 'System Unit deleted.');
    }

    public function render()
    {
        return view('livewire.system-units.unit-table');
    }
}