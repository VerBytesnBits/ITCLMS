<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Activitylog\Models\Activity;
use Flux\Flux;
class ManageItem extends Component
{
    public $item; // The model instance
    public $modelClass; // e.g. App\Models\ComponentParts
    public $selectedAction = null;

    protected $rules = [
        'selectedAction' => 'required|string',
    ];

    public function mount($modelClass, $itemId)
    {
        $this->modelClass = $modelClass;
        $this->item = $modelClass::findOrFail($itemId);
    }

    public $retirementNotes; // bind this to a textarea in the modal



    public function performAction()
    {
        $this->validate();

        $actionDescription = '';

        switch ($this->selectedAction) {
            case 'junk':
                $this->item->status = 'Junk';
                $this->item->save();
                $actionDescription = 'Marked as Junk';
                break;
            case 'dispose':
                $this->item->status = 'Disposed';
                $this->item->save();
                $actionDescription = 'Disposed';
                break;
            case 'salvage':
                $this->item->status = 'Salvaged';
                $this->item->save();
                $actionDescription = 'Salvaged';
                break;
            case 'decommission':
                $this->item->status = 'Decommissioned';
                $this->item->save();
                $actionDescription = 'Decommissioned';
                break;
            case 'archive':
                $this->item->delete();
                $actionDescription = 'Archived';
                break;
        }

        activity()
            ->performedOn($this->item)
            ->causedBy(auth()->user())
             ->withProperties([
                'action' => $this->selectedAction,
                'notes' => $this->retirementNotes,
                'status' => $this->item->status,
            ])
            ->log($actionDescription);
        
        $this->dispatch('swal', toast: true, icon: 'success', title: 'Action performed!', timer: 3000);
        Flux::modals()->close();
    }



    public function render()
    {
        return view('livewire.manage-item');
    }
}
