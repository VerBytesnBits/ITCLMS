<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use App\Models\SystemUnit;
use App\Models\Room;
use App\Support\PartsConfig;
use App\Livewire\SystemUnits\Traits\HandlesUnitEcho;
use App\Livewire\SystemUnits\Traits\HandlesUnitModals;
use App\Events\UnitCreated;

#[Layout('components.layouts.app', ['title' => 'Units'])]
class UnitIndex extends Component
{
    use HandlesUnitModals;

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
    public $units; // <--- add this

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

    // public function mount()
    // {
    //     $this->unitRelations = PartsConfig::unitRelations();

    //     foreach ($this->unitRelations as $relation) {
    //         $this->selectedComponents[$relation] = true;
    //     }

    //     $this->loadRooms();
    // }



    // #[On('echo:units,UnitDeleted')]
    // public function handleUnitDeleted($unitData)
    // {
    //     $this->dispatch('$refresh');
    // }

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

    #[On('refreshUnits')]
    public function refreshUnits()
    {

        $this->loadUnits();
    }

    public function loadUnits(): void
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
            default => array_merge(
                PartsConfig::componentTypes(),
                PartsConfig::peripheralTypes()
            )
        };

        $this->units = $unitsQuery
            ->with($relations)
            ->orderBy('name', 'asc')
            ->orderByRaw("CAST(SUBSTRING_INDEX(name, ' ', -1) AS UNSIGNED) asc")
            ->latest()
            ->get();
    }

    // public function getUnitsProperty()
    // {
    //     $user = Auth::user();

    //     if (!$user) {
    //         return collect();
    //     }

    //     if ($user->hasRole('lab_incharge')) {
    //         $unitsQuery = SystemUnit::with('room')
    //             ->whereHas('room', fn($q) => $q->where('lab_in_charge_id', $user->id));
    //     } elseif ($user->hasRole('chairman')) {
    //         $unitsQuery = SystemUnit::with('room');
    //     } else {
    //         return collect();
    //     }


    //     return $unitsQuery->latest()->get();
    // }


    public function getCountsProperty()
    {
        return [
            'operational' => SystemUnit::where('status', 'Operational')->count(),
            'non_operational' => SystemUnit::where('status', 'Non-Operational')->count(),
            'needs_repair' => SystemUnit::where('status', 'Needs Repair')->count(),
        ];
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
