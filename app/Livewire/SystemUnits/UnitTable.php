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
use Illuminate\Support\Collection;
use App\Models\Room;
class UnitTable extends Component

{

     /** @var Collection */
    public $units;
    /** @var Collection */
    public $rooms;

    // filters used by loadUnits(); adjust types as you prefer
    public string $filterRoomId = '';
    public string $filterStatus = '';
    public string $filterType = '';

    public function mount(): void
    {
        $this->rooms = $this->loadRooms();
        $this->units = $this->loadUnits();
    }

    private function loadRooms(): Collection
    {
        $user = Auth::user();

        return match (true) {
            !$user => collect(),
            $user->hasRole('lab_incharge') => Room::where('lab_in_charge_id', $user->id)->orderBy('name')->get(),
            $user->hasRole('chairman') => Room::orderBy('name')->get(),
            default => collect(),
        };
    }

    private function getRoomIdForQuery(): ?int
    {
        return $this->filterRoomId !== '' ? (int) $this->filterRoomId : null;
    }

    private function loadUnits(): Collection
    {
        $user = Auth::user();

        $query = match (true) {
            !$user => SystemUnit::query()->whereRaw('1 = 0'), // empty result if no user
            $user->hasRole('lab_incharge') => SystemUnit::with('room')
                ->whereHas('room', fn ($q) => $q->where('lab_in_charge_id', $user->id)),
            $user->hasRole('chairman') => SystemUnit::with('room'),
            default => SystemUnit::query()->whereRaw('1 = 0'),
        };

        if ($roomId = $this->getRoomIdForQuery()) {
            $query->where('room_id', $roomId);
        }

        if ($this->filterStatus !== '') {
            $query->where('status', $this->filterStatus);
        }

        $relations = match ($this->filterType) {
            'component'  => PartsConfig::componentTypes(),
            'peripheral' => PartsConfig::peripheralTypes(),
            default      => array_merge(PartsConfig::componentTypes(), PartsConfig::peripheralTypes()),
        };

        return $query->with($relations)
            ->orderBy('name', 'asc')
            ->orderByRaw("CAST(SUBSTRING_INDEX(name, '-', -1) AS UNSIGNED) asc")
            ->get();
    }

    #[On('refreshUnits')]
    public function refreshUnits()
    {
        $this->units = $this->loadUnits();
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
            if ($items instanceof Collection) {
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
        // event(new UnitDeleted($unit->id));
        // broadcast(new UnitDeleted($unit->id))->toOthers();
        broadcast(new UnitDeleted($id));
        // broadcast(new UnitDeleted(['id' => $id]))->toOthers();


        session()->flash('success', 'System Unit deleted.');
    }

    public function render()
    {
        return view('livewire.system-units.unit-table', [
            'units' => $this->units,
        ]);
    }
}