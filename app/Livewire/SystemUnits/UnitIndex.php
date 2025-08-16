<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use App\Models\SystemUnit;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Events\UnitCreated;
use App\Events\UnitUpdated;
use App\Events\UnitDeleted;
use App\Support\PartsConfig;

class UnitIndex extends Component
{
    #[Url(as: 'modal')]
    public ?string $modal = null;

    #[Url(as: 'id')]
    public ?int $id = null;

    // Filters (persisted in URL)
    #[Url(as: 'room', except: '')]
    public ?string $filterRoomId = '';

    #[Url(as: 'status', except: '')]
    public ?string $filterStatus = ''; // default empty = show all

    #[Url(as: 'Type', except: '')]
    public ?string $filterType = '';

    public $rooms;

    public $room_id;
    public $name;
    public $status = 'Operational';

    public ?SystemUnit $viewUnit = null;
    public $allParts;

    public bool $showSelectComponents = false;
    public bool $showPreview = false;
    public ?string $pdfBase64 = null;

    // Centralized list of all unit relations
    public array $unitRelations;

    // Default selected components (dynamically generated from $unitRelations)
    public array $selectedComponents = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'status' => 'required|string|in:Operational,Needs Repair,Non-Operational',
        'room_id' => 'required|exists:rooms,id',
    ];

    public function mount()
    {
        $this->unitRelations = PartsConfig::unitRelations();

        foreach ($this->unitRelations as $relation) {
            $this->selectedComponents[$relation] = true;
        }

        $this->loadRooms();
    }

    #[On('echo:units,UnitCreated')]
    public function handleUnitCreated($unitData)
    {
        // Just refresh Livewire view — units are queried dynamically
        $this->dispatch('$refresh');
    }

    // #[On('echo:units,UnitUpdated')]
    // public function handleUnitUpdated($unitData)
    // {

    // }
    #[On('echo:units,UnitUpdated')]
    public function handleUnitUpdated($unitData)
    {
        // Make sure $units is a Collection of models
        $this->units = $this->units->map(function ($unit) use ($unitData) {
            if ($unit->id === $unitData['id']) {
                $unit->status = $unitData['status']; // update status
            }
            return $unit;
        });

        $this->dispatch('$refresh'); // optional if you need UI refresh
    }

    public function updateUnitStatus($unitId, $newStatus)
    {
        $unit = SystemUnit::findOrFail($unitId);
        $unit->status = $newStatus;
        $unit->save();

        // Broadcast to others
        broadcast(new UnitUpdated($unit))->toOthers();
    }


    #[On('echo:units,UnitDeleted')]
    public function handleUnitDeleted($unitData)
    {
        $this->dispatch('$refresh');
    }

    private function loadRooms()
    {
        $user = Auth::user();

        if (!$user) {
            $this->rooms = collect();
            return;
        }

        if ($user->hasRole('lab_incharge')) {
            $roomsQuery = Room::where('lab_in_charge_id', $user->id)->orderBy('name');
        } elseif ($user->hasRole('chairman')) {
            $roomsQuery = Room::orderBy('name');
        } else {
            $this->rooms = collect();
            return;
        }

        $this->rooms = $roomsQuery->get();
    }

    public function getRoomIdForQuery(): ?int
    {
        return $this->filterRoomId !== '' ? (int) $this->filterRoomId : null;
    }

    //  Dynamic query for units
    public function getUnitsProperty()
    {
        $user = Auth::user();

        if (!$user) {
            return collect();
        }

        if ($user->hasRole('lab_incharge')) {
            $unitsQuery = SystemUnit::with('room')
                ->whereHas('room', fn($q) => $q->where('lab_in_charge_id', $user->id));
        } elseif ($user->hasRole('chairman')) {
            $unitsQuery = SystemUnit::with('room');
        } else {
            return collect();
        }

        // Apply filters dynamically
        // if ($this->filterRoomId) {
        //     $unitsQuery->where('room_id', $this->filterRoomId);
        // }
        if ($filterRoomId = $this->getRoomIdForQuery()) {
            $unitsQuery->where('room_id', $filterRoomId);
        }




        if ($this->filterStatus) {
            $unitsQuery->where('status', $this->filterStatus);
        }




        if ($this->filterType) {
            $unitsQuery->where('type', $this->filterType);
        }

        return $unitsQuery->latest()->get();
    }

    //  Legend counts (always fresh)
    public function getCountsProperty()
    {
        return [
            'operational' => SystemUnit::where('status', 'Operational')->count(),
            'non_operational' => SystemUnit::where('status', 'Non-Operational')->count(),
            'needs_repair' => SystemUnit::where('status', 'Needs Repair')->count(),
        ];
    }

    public function openManageModal($id)
    {
        $this->id = $id;
        $this->modal = 'manage';
    }

    public function openCreateModal()
    {
        $this->reset(['id', 'name', 'status', 'room_id']);
        $this->modal = 'create';
    }

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

        $this->loadAllParts();
        $this->modal = 'view';
    }

    private function loadAllParts()
    {
        $allParts = collect();
        foreach ($this->unitRelations as $relation) {
            $relationData = $this->viewUnit->$relation ?? null;
            if ($relationData) {
                $allParts = $allParts->concat(
                    $relationData instanceof \Illuminate\Support\Collection
                    ? $relationData
                    : collect([$relationData])
                );
            }
        }
        $this->allParts = $this->recursiveFlatten($allParts);
    }

    private function recursiveFlatten($collection)
    {
        $result = collect();
        foreach ($collection as $item) {
            if ($item instanceof \Illuminate\Support\Collection) {
                $result = $result->concat($this->recursiveFlatten($item));
            } else {
                $result->push($item);
            }
        }
        return $result;
    }

    #[On('closeModal')]
    public function closeModal()
    {
        $this->modal = null;
        $this->reset(['id', 'name', 'status', 'room_id', 'viewUnit', 'allParts']);
    }

    public function createUnit()
    {
        $this->validate();

        if (
            Auth::user()->hasRole('lab_incharge') &&
            !$this->rooms->pluck('id')->contains($this->room_id)
        ) {
            abort(403, 'Unauthorized room assignment.');
        }

        $unit = SystemUnit::with('room')->create([
            'room_id' => $this->room_id,
            'name' => $this->name,
            'status' => $this->status,
        ])->fresh(['room']);

        broadcast(new UnitCreated($unit))->toOthers();

        $this->modal = null;
        session()->flash('success', 'System Unit created successfully.');
    }

    public function updateUnit()
    {
        $this->validate();

        if (
            Auth::user()->hasRole('lab_incharge') &&
            !$this->rooms->pluck('id')->contains($this->room_id)
        ) {
            abort(403, 'Unauthorized room assignment.');
        }

        $unit = SystemUnit::findOrFail($this->id);
        $unit->update([
            'room_id' => $this->room_id,
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $unit = $unit->fresh(['room']);

        broadcast(new UnitUpdated($unit))->toOthers();

        $this->modal = null;
        session()->flash('success', 'System Unit updated successfully.');
    }

    public function deleteUnit($id)
    {
        $unit = SystemUnit::with($this->unitRelations)->findOrFail($id);

        if (
            Auth::user()->hasRole('lab_incharge') &&
            $unit->room->lab_in_charge_id !== Auth::id()
        ) {
            abort(403, 'Unauthorized action.');
        }

        // Nullify all related components/peripherals
        foreach ($this->unitRelations as $relation) {
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
        broadcast(new UnitDeleted(['id' => $id]))->toOthers();
        session()->flash('success', 'System Unit deleted.');
    }

    #[On('viewUnits')]
    public function filterByRoom($roomId)
    {
        $this->filterRoomId = $roomId;
        $this->modal = 'viewRoomUnits'; // optional
    }

    public function render()
    {
        return view('livewire.system-units.unit-index', [
            'units' => $this->units, // dynamic from getUnitsProperty
            'rooms' => $this->rooms,
            'viewUnit' => $this->viewUnit,
            'allParts' => $this->allParts,
        ]);
    }
}
