<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Livewire\Attributes\On;

class DecommissionedUnitsModal extends Component
{
    public bool $show = false;

    // Use proper Eloquent Collection type
    public EloquentCollection $units;

    public array $expandedUnits = [];
    public array $selectedComponents = [];
    public array $selectedPeripherals = [];

    #[On('toggle-decommissioned-units')]
    public function toggle()
    {
        $this->show = !$this->show;

        if ($this->show) {
            $this->loadUnits();
        }
    }

    public function mount()
    {
        $this->units = new EloquentCollection(); // Empty Eloquent Collection
    }

    public function loadUnits()
    {
        $user = auth()->user();

        $query = SystemUnit::onlyTrashed()
            ->with([
                'components' => fn($q) => $q->onlyTrashed(),
                'peripherals' => fn($q) => $q->onlyTrashed(),
            ]);

        if (!$user->hasRole('chairman')) {
            $roomIds = $user->rooms->pluck('id');
            $query->whereIn('room_id', $roomIds);
        }

        $this->units = $query->orderBy('deleted_at', 'desc')->get();

        // Initialize selected arrays for each unit
        foreach ($this->units as $unit) {
            $unit->components = $unit->components ?? new EloquentCollection();
            $unit->peripherals = $unit->peripherals ?? new EloquentCollection();

            $this->selectedComponents[$unit->id] = [];
            $this->selectedPeripherals[$unit->id] = [];
        }
    }

    public function toggleUnitExpansion(int $unitId)
    {
        if (in_array($unitId, $this->expandedUnits)) {
            $this->expandedUnits = array_diff($this->expandedUnits, [$unitId]);
        } else {
            $this->expandedUnits[] = $unitId;
        }
    }

    public function restoreSelectedChildren(int $unitId)
    {
        $unit = $this->units->firstWhere('id', $unitId);
        if (!$unit) return;

        // Restore selected components
        $componentIds = $this->selectedComponents[$unitId] ?? [];
        if (!empty($componentIds)) {
            $unit->components()->onlyTrashed()->whereIn('id', $componentIds)->restore();
        }

        // Restore selected peripherals
        $peripheralIds = $this->selectedPeripherals[$unitId] ?? [];
        if (!empty($peripheralIds)) {
            $unit->peripherals()->onlyTrashed()->whereIn('id', $peripheralIds)->restore();
        }

        // Restore parent unit only if no children selected
        if (empty($componentIds) && empty($peripheralIds)) {
            $unit->restore();
        }

        // Reload units
        $this->loadUnits();

        $this->dispatch('toast', [
            'icon' => 'success',
            'title' => 'Selected items restored successfully!'
        ]);
    }

    public function render()
    {
        return view('livewire.system-units.decommissioned-units-modal');
    }
}
