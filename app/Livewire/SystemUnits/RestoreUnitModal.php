<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;

class RestoreUnitModal extends Component
{
    public bool $show = false;
    public ?SystemUnit $unit = null;
    public array $selectedComponents = [];
    public array $selectedPeripherals = [];

    #[\Livewire\Attributes\On('open-restore-unit-modal')]
    public function openModal(int $unitId)
    {
        $this->unit = SystemUnit::withTrashed()->with([
            'components' => fn($q) => $q->onlyTrashed(),
            'peripherals' => fn($q) => $q->onlyTrashed()
        ])->find($unitId);

        if (!$this->unit) return;

        $this->selectedComponents = $this->unit->components->pluck('id')->toArray();
        $this->selectedPeripherals = $this->unit->peripherals->pluck('id')->toArray();
        $this->show = true;
    }

    public function restore()
    {
        if (!$this->unit) return;

        $this->unit->restore();

        $this->unit->components()->onlyTrashed()
            ->whereIn('id', $this->selectedComponents)
            ->restore();

        $this->unit->peripherals()->onlyTrashed()
            ->whereIn('id', $this->selectedPeripherals)
            ->restore();

        $this->dispatch('toast', ['icon'=>'success','title'=>'Restored successfully']);
        $this->dispatch('toggle-decommissioned-units'); // refresh list
        $this->close();
    }

    public function close()
    {
        $this->show = false;
        $this->unit = null;
        $this->selectedComponents = [];
        $this->selectedPeripherals = [];
    }

    public function render()
    {
        return view('livewire.system-units.restore-unit-modal');
    }
}
