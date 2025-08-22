<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use App\Models\SystemUnit;
use App\Models\Room;
use App\Support\PartsConfig;
use App\Livewire\SystemUnits\Traits\HandlesUnitEcho;
use App\Livewire\SystemUnits\Traits\HandlesUnitModals;
use App\Events\UnitCreated;

class UnitIndex extends Component
{
    use HandlesUnitEcho, HandlesUnitModals;

    #[Url(as: 'modal', except: null)]
    public ?string $modal = null;

    #[Url(as: 'id', except: null)]
    public ?int $id = null;

    #[Url(as: 'room', except: '')]
    public ?string $filterRoomId = '';

    #[Url(as: 'status', except: '')]
    public ?string $filterStatus = '';

    #[Url(as: 'Type', except: '')]
    public ?string $filterType = '';

    public $rooms;
    public $room_id;
    public $name;
    public $status = '';
    public ?SystemUnit $viewUnit = null;

    public array $unitRelations;
    public $units; // Collection of SystemUnit models

    protected $rules = [
        'name' => 'required|string|max:255',
        'status' => 'required|string|in:Operational,Needs Repair,Non-Operational',
        'room_id' => 'required|exists:rooms,id',
    ];


    public function mount()
    {
        $this->unitRelations = PartsConfig::unitRelations();
        $this->loadRooms();
        $this->loadUnits(); // <-- initial load
    }


    private function loadRooms()
    {
        $user = Auth::user();

        $this->rooms = match (true) {
            !$user => collect(),
            $user->hasRole('lab_incharge') => Room::where('lab_in_charge_id', $user->id)->orderBy('name')->get(),
            $user->hasRole('chairman') => Room::orderBy('name')->get(),
            default => collect()
        };
    }

    private function getRoomIdForQuery(): ?int
    {
        return $this->filterRoomId !== '' ? (int) $this->filterRoomId : null;

    }
    // #[On('echo:units,UnitCreated')]
    // #[On('echo:units,UnitUpdated')]
    // #[On('echo:units,UnitDeleted')]
    // public function refreshUnits()
    // {
    //     logger('Livewire heard UnitUpdated!');
    //     $this->loadUnits(); // refresh data in memory
    // }
    // #[On('unit-saved')]
    // #[On('unit-updated')]
    // #[On('echo:units,UnitCreated')]
    // #[On('echo:units,UnitUpdated')]
    // #[On('echo:units,UnitDeleted')]
    // public function refreshUnits()
    // {
    //     $this->loadUnits(); // reload from DB
    // }
    #[On('echo:units,UnitCreated')]
    public function handleRealtimeUnitCreated($unit)
    {
        // Reload units when event is received
        $this->loadUnits();
    }

    public function loadUnits()
    {

        $user = Auth::user();

        $unitsQuery = match (true) {
            $user->hasRole('lab_incharge') => SystemUnit::with('room')
                ->whereHas('room', fn($q) => $q->where('lab_in_charge_id', $user->id)),
            $user->hasRole('chairman') => SystemUnit::with('room'),
            default => null,
        };

        if (!$unitsQuery) {
            $this->units = collect();
            return;
        }

        if ($filterRoomId = $this->getRoomIdForQuery()) {
            $unitsQuery->where('room_id', $filterRoomId);
        }

        if ($this->filterStatus) {
            $unitsQuery->where('status', $this->filterStatus);
        }

        $relations = match ($this->filterType) {
            'component' => PartsConfig::componentTypes(),
            'peripheral' => PartsConfig::peripheralTypes(),
            default => array_merge(PartsConfig::componentTypes(), PartsConfig::peripheralTypes())
        };

        $this->units = $unitsQuery
            ->with($relations)
            ->orderBy('name', 'asc')
            ->orderByRaw("CAST(SUBSTRING_INDEX(name, ' ', -1) AS UNSIGNED) asc")
            ->get();
    }

    public function getCountsProperty()
    {
        return [
            'operational' => SystemUnit::where('status', 'Operational')->count(),
            'non_operational' => SystemUnit::where('status', 'Non-Operational')->count(),
            'needs_repair' => SystemUnit::where('status', 'Needs Repair')->count(),
        ];
    }

    // public function createUnit()
    // {
    //     $this->validate();

    //     if (Auth::user()->hasRole('lab_incharge') && !$this->rooms->pluck('id')->contains($this->room_id)) {
    //         abort(403, 'Unauthorized room assignment.');
    //     }

    //     $unit = SystemUnit::with('room')->create([
    //         'room_id' => $this->room_id,
    //         'name' => $this->name,
    //         'status' => $this->status,
    //     ])->fresh(['room']);
    //     event(new UnitCreated($unit));
    //     // event(new \App\Events\UnitCreated($unit));


    //     $this->modal = null;
    //     session()->flash('success', 'System Unit created successfully.');
    // }


    public function updateUnit()
    {
        $this->validate();

        if (Auth::user()->hasRole('lab_incharge') && !$this->rooms->pluck('id')->contains($this->room_id)) {
            abort(403, 'Unauthorized room assignment.');
        }

        $unit = SystemUnit::findOrFail($this->id);
        $unit->update([
            'room_id' => $this->room_id,
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $unit = $unit->fresh(['room']);
        event(new \App\Events\UnitUpdated($unit));

        $this->modal = null;
        session()->flash('success', 'System Unit updated successfully.');
    }



    public function render()
    {
        return view('livewire.system-units.unit-index', [
            'units' => $this->units,   // <--- use the reactive property
            'rooms' => $this->rooms,
            'viewUnit' => $this->viewUnit,
        ]);
    }

}
