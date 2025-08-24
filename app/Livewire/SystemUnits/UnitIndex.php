<?php

namespace App\Livewire\SystemUnits;

use App\Livewire\SystemUnits\Traits\HandlesUnitEcho;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\SystemUnit;
use App\Models\Room;
use App\Support\PartsConfig;
use App\Livewire\SystemUnits\Traits\HandlesUnitModals;

#[Layout('components.layouts.app', ['title' => 'Units'])]
class UnitIndex extends Component
{
    use HandlesUnitModals, HandlesUnitEcho;

    // URL parameters
    #[Url(as: 'modal', except: null)] public ?string $modal = null;
    #[Url(as: 'id', except: null)] public ?int $id = null;
    #[Url(as: 'room', except: '')] public ?string $filterRoomId = '';
    #[Url(as: 'status', except: '')] public ?string $filterStatus = '';
    #[Url(as: 'Type', except: '')] public ?string $filterType = '';

    // Reactive properties
    public $units;             // main table collection
    public $rooms;             // rooms dropdown
    public ?SystemUnit $viewUnit = null;
    public array $unitRelations;

    public $name;
    public $room_id;
    public $status = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'status' => 'required|string|in:Operational,Needs Repair,Non-Operational',
        'room_id' => 'required|exists:rooms,id',
    ];

    public function mount()
    {
        $this->unitRelations = PartsConfig::unitRelations();
        $this->loadRooms();
       
    }

    private function loadRooms(): void
    {
        $user = Auth::user();
        $this->rooms = match (true) {
            !$user => collect(),
            $user->hasRole('lab_incharge') => Room::where('lab_in_charge_id', $user->id)->orderBy('name')->get(),
            $user->hasRole('chairman') => Room::orderBy('name')->get(),
            default => collect(),
        };
    }
   
    #[On('refreshUnits')]
    public function refreshUnits()
    {
       
    }
    // Status counts
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
            'units' => $this->units,
            'rooms' => $this->rooms,
            'viewUnit' => $this->viewUnit,
        ]);
    }
}
