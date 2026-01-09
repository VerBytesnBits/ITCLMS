<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;
use Livewire\Attributes\On;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteModal extends Component
{
    public bool $show = false;
    public ?int $systemUnitId = null;
    public string $action = '';
    public ?string $unitName = null;

    #[On('confirm-delete-system-unit')]
    public function openModal(int $id)
    {
        try {
            $unit = SystemUnit::findOrFail($id);

            $this->systemUnitId = $id;
            $this->unitName = $unit->name ?? 'Unnamed Unit';
            $this->action = '';
            $this->show = true;

        } catch (ModelNotFoundException $e) {
            $this->dispatch('swal', [
                'toast' => true,
                'icon' => 'error',
                'title' => 'System Unit not found.',
                'timer' => 3000,
            ]);
        }
    }

    public function cancel()
    {
        $this->reset(['show', 'action', 'systemUnitId', 'unitName']);
    }

    public function confirmAction()
    {
        if (!$this->action) {
            $this->dispatch('swal', [
                'toast' => true,
                'icon' => 'error',
                'title' => 'Please select an action before confirming.',
                'timer' => 3000,
            ]);
            return;
        }

        $unit = SystemUnit::find($this->systemUnitId);

        if (!$unit) {
            $this->dispatch('swal', [
                'toast' => true,
                'icon' => 'error',
                'title' => 'System Unit not found.',
                'timer' => 3000,
            ]);
            return;
        }

        try {
            match ($this->action) {
                'delete' => $unit->forceDelete(),
                'decommission' => $this->decommissionUnit($unit),
                'mark_defective' => $unit->update(['status' => 'Non-Operational']),
                default => null,
            };

            $this->dispatch('unit-deleted'); 

            
            $messages = [
                'delete' => 'System Unit permanently deleted.',
                'decommission' => 'System Unit successfully decommissioned.',
                'mark_defective' => 'System Unit marked as defective.',
            ];

            $this->dispatch('swal', [
                'toast' => true,
                'icon' => 'success',
                'title' => $messages[$this->action] ?? 'Action completed successfully.',
                'timer' => 3000,
            ]);

            $this->cancel();

        } catch (\Throwable $e) {
            $this->dispatch('swal', [
                'toast' => true,
                'icon' => 'error',
                'title' => 'Something went wrong. Please try again.',
                'timer' => 3000,
            ]);
        }
    }

    protected function decommissionUnit($unit)
    {
   

        
        $unit->components()->update(['status' => 'Decommission']);

        
        $unit->peripherals()->update(['status' => 'Decommission']);

       
        $unit->delete();
    }

    public function render()
    {
        return view('livewire.system-units.delete-modal');
    }
}
