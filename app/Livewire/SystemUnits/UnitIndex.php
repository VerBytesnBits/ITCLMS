<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use App\Models\SystemUnit;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;


class UnitIndex extends Component
{
    #[Url(as: 'modal')]
    public ?string $modal = null;

    #[Url(as: 'id')]
    public ?int $id = null;

    public $units;
    public $rooms;

    public $room_id;
    public $name;
    public $status = 'Working';

    public ?SystemUnit $viewUnit = null; // Holds the unit to view

    public $allParts; // flattened collection of all components and peripherals

    protected $rules = [
        'name' => 'required|string|max:255',
        'status' => 'required|string|in:Working,Under Maintenance,Decommissioned',
        'room_id' => 'required|exists:rooms,id',
    ];

    public function mount()
    {
        $this->refreshUnits();
        $this->rooms = Room::orderBy('name')->get();
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
        $this->id = $unit->id;
        $this->name = $unit->name;
        $this->status = $unit->status;
        $this->room_id = $unit->room_id;
        $this->modal = 'edit';
    }

    public function openViewModal($id)
    {
        // Load the unit with components and peripherals eagerly loaded
        $this->viewUnit = SystemUnit::with([
            'processor',
            'cpuCooler',
            'motherboard',
            'memory',
            'graphicsCard',
            'm2Ssd',
            'sataSsd',
            'hardDiskDrive',
            'powerSupply',
            'computerCase',
            'keyboard',
            'mouse',
            'headset',
            'speaker',
            'webCamera',
            'monitor',
            'room'
        ])->findOrFail($id);

        // Prepare flattened list of all parts
        $this->loadAllParts();

        $this->modal = 'view';
    }

    private function loadAllParts()
    {
        $types = [
            'processor',
            'cpuCooler',
            'motherboard',
            'memory',
            'graphicsCard',
            'm2Ssd',
            'sataSsd',
            'hardDiskDrive',
            'powerSupply',
            'computerCase',
            'keyboard',
            'mouse',
            'headset',
            'speaker',
            'webCamera',
            'monitor',
        ];

        $allParts = collect();

        foreach ($types as $type) {
            $relation = $this->viewUnit->$type ?? null;

            if ($relation) {
                if ($relation instanceof \Illuminate\Support\Collection) {
                    $allParts = $allParts->concat($relation);
                } else {
                    $allParts->push($relation);
                }
            }
        }

        // Recursive flatten helper (in case some relations return nested collections)
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


    #[On('unitCreated')]
    #[On('unitUpdated')]
    #[On('unitDeleted')]
    public function refreshUnits()
    {
        $user = Auth::user();

        if (!$user) {
            $this->units = collect();
            return;
        }

        if ($user->hasRole('lab_incharge')) {
            // Only units in rooms assigned to this lab in-charge
            $this->units = SystemUnit::with('room')
                ->whereHas('room', function ($q) use ($user) {
                    $q->where('lab_in_charge_id', $user->id);
                })
                ->latest()
                ->get();

            // Also limit rooms dropdown
            $this->rooms = Room::where('lab_in_charge_id', $user->id)
                ->orderBy('name')
                ->get();

        } elseif ($user->hasRole('chairman')) {
            // All units and rooms
            $this->units = SystemUnit::with('room')->latest()->get();
            $this->rooms = Room::orderBy('name')->get();

        } else {
            // No access
            $this->units = collect();
            $this->rooms = collect();
        }
    }

    public function createUnit()
    {
        $this->validate();

        SystemUnit::create([
            'room_id' => $this->room_id,
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $this->modal = null;
        session()->flash('success', 'System Unit created successfully.');

        $this->refreshUnits();
        $this->dispatch('unitCreated');
    }

    public function updateUnit()
    {
        $this->validate();

        $unit = SystemUnit::findOrFail($this->id);
        $unit->update([
            'room_id' => $this->room_id,
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $this->modal = null;
        session()->flash('success', 'System Unit updated successfully.');

        $this->refreshUnits();
        $this->dispatch('unitUpdated');
    }

    public function deleteUnit($id)
    {
        SystemUnit::findOrFail($id)->delete();
        session()->flash('success', 'System Unit deleted.');
        $this->refreshUnits();
        $this->dispatch('unitDeleted');
    }
    

    public function render()
    {
        return view('livewire.system-units.unit-index', [
            'units' => $this->units,
            'rooms' => $this->rooms,
            'viewUnit' => $this->viewUnit,
            'allParts' => $this->allParts,
        ]);
    }

    
}
