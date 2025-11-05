<?php

namespace App\Livewire\Rooms;

use Livewire\Component;
use App\Models\Room;
use App\Models\User;

class AssignTechnician extends Component
{
    public ?Room $room = null; // nullable
    public $roomId;            // store room id from parent
    public $selectedTechnicianIds = [];

    public function mount($roomId)
    {
        $this->roomId = $roomId;
        $this->room = Room::findOrFail($roomId);

        // prefill selected technicians
        $this->selectedTechnicianIds = $this->room->users()
            ->wherePivot('role_in_room', 'lab_technician')
            ->pluck('users.id')
            ->toArray();
    }

    public function save()
    {
        // Authorization: only Lab In-Charge or Chairman
        $isLabIncharge = $this->room->users()
            ->wherePivot('role_in_room', 'lab_incharge')
            ->where('user_id', auth()->id())
            ->exists();

        if (!$isLabIncharge && !auth()->user()->hasRole('chairman')) {
            abort(403, 'Unauthorized');
        }

        // Get current technicians assigned
        $currentTechIds = $this->room->users()
            ->wherePivot('role_in_room', 'lab_technician')
            ->pluck('users.id')
            ->toArray();

        // Compute new technicians to attach
        $toAttach = array_diff($this->selectedTechnicianIds, $currentTechIds);
        // Compute technicians to detach
        $toDetach = array_diff($currentTechIds, $this->selectedTechnicianIds);

        // Detach removed technicians
        if (!empty($toDetach)) {
            $this->room->users()->wherePivot('role_in_room', 'lab_technician')
                ->whereIn('user_id', $toDetach)
                ->detach();
        }

        // Attach new technicians with pivot
        foreach ($toAttach as $id) {
            $this->room->users()->attach($id, ['role_in_room' => 'lab_technician']);
        }

        $this->dispatch('swal', toast: true, icon: 'success', title: 'Technician(s) assigned successfully');
        $this->dispatch('closeModal');
        $this->dispatch('roomUpdated');
    }
   


    public function render()
    {
        return view('livewire.rooms.assign-technician', [
            'technicianOptions' => User::role('lab_technician')->pluck('name', 'id')->toArray(),
        ]);
    }
}
