<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;

class DecommissionedUnits extends Component
{
    public bool $show = false;

    // Always arrays for safe foreach/count
    public array $units = [];
    public array $expandedUnits = [];
    public array $selectedComponents = [];
    public array $selectedPeripherals = [];

    // Toggle visibility
    public function toggle()
    {
        $this->show = !$this->show;
        if ($this->show) {
            $this->loadUnits();
        }
    }

    // Load decommissioned units with soft-deleted children
    public function loadUnits()
    {
        $user = auth()->user();

        $query = SystemUnit::onlyTrashed()->with([
            'components' => fn($q) => $q->onlyTrashed(),
            'peripherals' => fn($q) => $q->onlyTrashed()
        ]);

        if (!$user->hasRole('chairman')) {
            $roomIds = $user->rooms->pluck('id');
            $query->whereIn('room_id', $roomIds);
        }

        $this->units = $query->orderBy('deleted_at', 'desc')->get()->map(function ($unit) {
            return [
                'id' => $unit->id,
                'name' => $unit->name,
                'deleted_at' => $unit->deleted_at?->format('Y-m-d H:i'),
                'components' => $unit->components->toArray() ?? [],
                'peripherals' => $unit->peripherals->toArray() ?? [],
            ];
        })->toArray();
    }

    // Expand/Collapse unit to show children
    public function toggleUnitExpansion($unitId)
    {
        // Initialize checkbox arrays for this unit
        $this->selectedComponents[$unitId] = $this->selectedComponents[$unitId] ?? [];
        $this->selectedPeripherals[$unitId] = $this->selectedPeripherals[$unitId] ?? [];

        if (in_array($unitId, $this->expandedUnits)) {
            $this->expandedUnits = array_diff($this->expandedUnits, [$unitId]);
        } else {
            $this->expandedUnits[] = $unitId;
        }
    }

    public function restoreUnitOrChildren($unitId)
    {
        $unit = SystemUnit::withTrashed()->find($unitId);
        if (!$unit)
            return;

        $components = $this->selectedComponents[$unitId] ?? [];
        $peripherals = $this->selectedPeripherals[$unitId] ?? [];

        if (!empty($components) || !empty($peripherals)) {
            // Restore only selected children
            if (!empty($components)) {
                $unit->components()
                    ->onlyTrashed()
                    ->whereIn('id', $components)
                    ->restore();
            }

            if (!empty($peripherals)) {
                $unit->peripherals()
                    ->onlyTrashed()
                    ->whereIn('id', $peripherals)
                    ->restore();
            }

            $this->dispatch('toast', [
                'icon' => 'success',
                'title' => 'Selected components/peripherals restored successfully.'
            ]);
        } else {
            // Restore parent unit along with all its children
            $unit->restore(); // restores parent

            $unit->components()->onlyTrashed()->restore();
            $unit->peripherals()->onlyTrashed()->restore();

            $this->dispatch('toast', [
                'icon' => 'success',
                'title' => 'Unit and all its components/peripherals restored successfully.'
            ]);
        }

        // Refresh the decommissioned units list
        $this->loadUnits();

        // Clear selections for this unit
        $this->selectedComponents[$unitId] = [];
        $this->selectedPeripherals[$unitId] = [];
    }



    public function render()
    {
        return view('livewire.system-units.decommissioned-units');
    }
}
