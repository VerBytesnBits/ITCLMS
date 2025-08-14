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
    public $status = 'Operational';

    public ?SystemUnit $viewUnit = null;
    public $allParts;

    public bool $showSelectComponents = false;
    public bool $showPreview = false;
    public ?string $pdfBase64 = null;
    public ?int $filterRoomId = null; // selected room for filtering


    // Centralized list of all unit relations
    public array $unitRelations = [
        'processor',
        'cpuCooler',
        'motherboard',
        'memories',
        'graphicsCards',
        'powerSupply',
        'computerCase',
        'm2Ssds',
        'sataSsds',
        'hardDiskDrives',
        'monitor',
        'keyboard',
        'mouse',
        'headset',
        'speaker',
        'webCamera',
    ];

    // Default selected components (dynamically generated from $unitRelations)
    public array $selectedComponents = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'status' => 'required|string|in:Operational,Needs Repair,Non-Operational',
        'room_id' => 'required|exists:rooms,id',
    ];

    public function mount()
    {
        // Initialize selectedComponents dynamically
        foreach ($this->unitRelations as $relation) {
            $this->selectedComponents[$relation] = true;
        }

        $this->loadUnitsAndRooms();
    }

    public function openSelectComponentsModal()
    {
        $this->showSelectComponents = true;
    }

    public function confirmComponentSelection()
    {
        $this->showSelectComponents = false;
        $this->previewPdf();
    }

    public function previewPdf()
    {
        $relations = array_keys(array_filter($this->selectedComponents));
        $relations = array_intersect($relations, $this->unitRelations);

        $units = SystemUnit::with($relations)->get();

        $pdf = Pdf::loadView('pdf.system-units', compact('units'))
            ->setPaper('a4', 'landscape');

        $this->pdfBase64 = base64_encode($pdf->output());
        $this->showPreview = true;
    }

    public function downloadPdf()
    {
        $relations = array_keys(array_filter($this->selectedComponents));
        $relations = array_intersect($relations, $this->unitRelations);

        $units = SystemUnit::with($relations)->get();

        $pdf = Pdf::loadView('pdf.system-units', compact('units'))
            ->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'system-units-report.pdf');
    }

    #[On('echo:units,UnitCreated')]
    public function handleUnitCreated($unitData)
    {
        $this->units->push(collect($unitData));
    }

    #[On('echo:units,UnitUpdated')]
    public function handleUnitUpdated($unitData)
    {
        $index = $this->units->search(fn($u) => $u['id'] === $unitData['id']);
        if ($index !== false) {
            $this->units[$index] = collect($unitData);
        } else {
            $this->units->push(collect($unitData));
        }
    }

    #[On('echo:units,UnitDeleted')]
    public function handleUnitDeleted($unitData)
    {
        $this->units = $this->units->reject(fn($u) => $u['id'] === $unitData['id']);
    }

    private function loadUnitsAndRooms()
    {
        $user = Auth::user();

        if (!$user) {
            $this->units = collect();
            $this->rooms = collect();
            return;
        }

        if ($user->hasRole('lab_incharge')) {
            $unitsQuery = SystemUnit::with('room')
                ->whereHas('room', fn($q) => $q->where('lab_in_charge_id', $user->id));

            $roomsQuery = Room::where('lab_in_charge_id', $user->id)->orderBy('name');
        } elseif ($user->hasRole('chairman')) {
            $unitsQuery = SystemUnit::with('room');
            $roomsQuery = Room::orderBy('name');
        } else {
            $this->units = collect();
            $this->rooms = collect();
            return;
        }

        // Apply room filter if selected
        if ($this->filterRoomId) {
            $unitsQuery->where('room_id', $this->filterRoomId);
        }

        $this->units = $unitsQuery->latest()->get();
        $this->rooms = $roomsQuery->get();
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
        $this->loadUnitsAndRooms(); // reload units for this room
        $this->modal = 'viewRoomUnits'; // optional: open modal if you have one
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
