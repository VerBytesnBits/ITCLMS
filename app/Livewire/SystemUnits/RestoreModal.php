<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;
use App\Models\Peripheral;
use App\Models\ComponentParts;
use Livewire\Attributes\On;

class RestoreModal extends Component
{
    public bool $show = false;
    public ?int $unitId = null;
    public ?SystemUnit $unit = null;

    public bool $restoreParent = false;

    public array $restorePeripherals = [];  // selected peripheral IDs
    public array $restoreComponents = [];   // selected component IDs
    public array $childNewUnit = [];        // [childId => newUnitId]

    #[On('open-restore-unit-modal')]
    public function openModal(int $unitId)
    {
        $this->unitId = $unitId;
        $this->loadUnit();
        $this->show = true;
    }

    public function loadUnit()
    {
        $this->unit = SystemUnit::onlyTrashed()
            ->with([
                'peripherals' => fn($q) => $q->onlyTrashed(),
                'components' => fn($q) => $q->onlyTrashed()
            ])
            ->find($this->unitId);
    }

    public function restoreSelected()
    {
        if ($this->restoreParent && $this->unit) {
            $this->unit->restore();
            activity()
                ->performedOn($this->unit)
                ->causedBy(auth()->user())
                ->log('System Unit restored');
        }

        // Restore selected peripherals
        foreach ($this->restorePeripherals as $pId) {
            $p = Peripheral::withTrashed()->find($pId);
            $newUnit = $this->childNewUnit[$pId] ?? $p->system_unit_id;
            $p->restoreToUnit($newUnit);
        }

        // Restore selected components
        foreach ($this->restoreComponents as $cId) {
            $c = ComponentParts::withTrashed()->find($cId);
            $newUnit = $this->childNewUnit[$cId] ?? $c->system_unit_id;
            $c->restoreToUnit($newUnit);
        }

        $this->reset(['show', 'unit', 'restoreParent', 'restoreComponents', 'restorePeripherals', 'childNewUnit']);
        $this->dispatch('toast', [
            'icon' => 'success',
            'title' => 'Selected items restored successfully.'
        ]);

        $this->dispatch('unit-restored'); // notify parent component
    }

    public function cancel()
    {
        $this->reset(['show', 'unit', 'restoreParent', 'restoreComponents', 'restorePeripherals', 'childNewUnit']);
    }

    public function render()
    {
        return view('livewire.system-units.restore-modal');
    }
}
