<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;
use Livewire\Attributes\On;

class DeleteModal extends Component
{
    public bool $show = false;
    public ?int $systemUnitId = null;
    public string $action = '';
    public ?string $unitName = null;

    #[On('confirm-delete-system-unit')]
    public function openModal(int $id)
    {
        $unit = SystemUnit::find($id);

        if (!$unit) {
            $this->dispatch('toast', [
                'icon' => 'error',
                'title' => 'System Unit not found.'
            ]);
            return;
        }

        $this->systemUnitId = $id;
        $this->unitName = $unit->name ?? 'Unnamed Unit';
        $this->action = '';
        $this->show = true;
    }

    public function cancel()
    {
        $this->reset(['show', 'action', 'systemUnitId', 'unitName']);
    }

    public function confirmAction()
    {
        if (!$this->action) {
            $this->dispatch('toast', [
                'icon' => 'error',
                'title' => 'Please select an action before confirming.'
            ]);
            return;
        }

        $unit = SystemUnit::find($this->systemUnitId);

        if (!$unit) {
            $this->dispatch('toast', [
                'icon' => 'error',
                'title' => 'System Unit not found.'
            ]);
            return;
        }

        match ($this->action) {
            'delete' => $unit->forceDelete(), // permanently remove

            'decommission' => $this->decommissionUnit($unit), // handle all relations

            'mark_defective' => $unit->update(['status' => 'Defective']),

            default => null,
        };



        $this->dispatch('unit-deleted'); // refresh in parent component
        $this->dispatch('toast', [
            'icon' => 'success',
            'title' => 'Action completed successfully.'
        ]);

        $this->cancel();
    }
    protected function decommissionUnit($unit)
    {
        // Update the unit’s own status
        $unit->update(['status' => 'Decommissioned']);

        // Update all linked components
        $unit->components()->update(['status' => 'Decommissioned']);

        // Update all linked peripherals
        $unit->peripherals()->update(['status' => 'Decommissioned']);

        // Finally soft delete the unit
        $unit->delete();
    }

    public function render()
    {
        return view('livewire.system-units.delete-modal');
    }
}
