<?php

namespace App\Livewire\Rooms;

use Livewire\Component;
use App\Models\Room;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;

class RoomIndex extends Component
{
    #[Url(as: 'modal')]
    public ?string $modal = null;   // controls which modal is open

    #[Url(as: 'id')]
    public ?int $id = null;         // the selected room id

    public $rooms;
    public $roomId;

    public function mount()
    {
        $this->refreshRooms();
    }

    public function openCreateModal()
    {
        $this->id = null;
        $this->modal = 'create';
    }

    public function openEditModal($id)
    {
        $this->id = $id;
        $this->modal = 'edit';

    }

    public function openAssignLabIncharge($roomId)
    {
        $this->id = $roomId;
        $this->modal = 'assign-lab-incharge';
    }

    public function openAssignTechnician($roomId)
    {
        $this->id = $roomId;
        $this->modal = 'assign-technician';
    }

    #[On('closeModal')]
    public function closeModal()
    {
        $this->modal = null;
        $this->id = null;
    }

    #[On('roomCreated')]
    #[On('roomUpdated')]
    #[On('roomDeleted')]
    public function refreshRooms()
    {
        $this->rooms = Room::with('users')->orderBy('id', 'asc')->get();
    }
    // Trigger confirmation modal
    public function confirmDeleteRoom($id)
    {
       $this->roomId = $id;
       $this->dispatch('delete-confirm');
    }

    #[On('deleteRoomConfirmed')]
    public function deleteRoom()
    {
        if ($this->roomId) {
            Room::findOrFail($this->roomId)->delete();

            $this->dispatch('roomDeleted'); // triggers the Swal success message
            $this->reset('roomId'); // optional: reset after deletion
        }
    }





    public function removeTechnician($roomId, $userId)
    {
        $room = Room::findOrFail($roomId);
        $room->users()->wherePivot('role_in_room', 'lab_technician')->detach($userId);
        $this->dispatch('swal', toast: true, icon: 'success', title: 'Lab Technician unassigned successfully.');

    }
    public function removeIncharge($roomId, $userId)
    {
        $room = Room::findOrFail($roomId);

        $room->users()
            ->wherePivot('role_in_room', 'lab_incharge')
            ->detach($userId);
        $this->dispatch('swal', toast: true, icon: 'success', title: 'Lab In-Charge unassigned successfully.');

    }

    public function render()
    {
        return view('livewire.rooms.room-index');
    }
}
