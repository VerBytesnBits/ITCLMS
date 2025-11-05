<?php

namespace App\Livewire\Components;

use Livewire\Component;

class DeleteModal extends Component
{
    public $show = false;
    public $modelType;
    public $modelId;
    public $selectedAction = null; // 'delete' | 'junk'

    protected $listeners = ['open-delete-modal' => 'openModal'];

    public function openModal($id, $model)
    {
        $this->resetErrorBag();
        $this->reset(['selectedAction']);
        $this->show = true;
        $this->modelType = $model;
        $this->modelId = $id;
    }

    public function confirmAction()
    {
        if (!$this->selectedAction || !$this->modelType || !$this->modelId)
            return;

        $modelClass = "App\\Models\\" . $this->modelType;

        // Handle bulk delete separately
        if ($this->modelId === 'bulk') {
            // Dispatch event for parent to handle bulk deletion
            $this->dispatch('confirm-bulk-delete', [
                'model' => $this->modelType,
                'action' => $this->selectedAction,
            ]);

            $this->show = false;
            $this->selectedAction = null;
            return;
        }

        // Single delete logic
        $model = $modelClass::find($this->modelId);
        if (!$model)
            return;

        match ($this->selectedAction) {
            'delete' => $model->forceDelete(),
            'junk' => tap($model, function ($m) {
                    $m->update(['status' => 'Junk']);
                    $m->delete();
                }),
            default => null,
        };

        $this->dispatch('item-deleted', [
            'model' => $this->modelType,
            'id' => $this->modelId,
            'action' => $this->selectedAction,
        ]);

        $this->dispatch('swal', [
            'icon' => $this->selectedAction === 'delete' ? 'success' : 'warning',
            'title' => $this->selectedAction === 'delete'
                ? "{$this->modelType} permanently deleted."
                : "{$this->modelType} moved to Junk.",
            'timer' => 2000,
        ]);

        $this->selectedAction = null;
        $this->show = false;
    }


    public function render()
    {
        return view('livewire.components.delete-modal');
    }
}
