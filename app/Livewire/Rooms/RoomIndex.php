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

    public function deleteRoom($id)
    {
        Room::findOrFail($id)->delete();
        $this->dispatch('swal', toast: true, icon: 'success', title: 'Room deleted successfully', timer: 3000);
        $this->dispatch('roomDeleted');
    }

    public function render()
    {
        return view('livewire.rooms.room-index');
    }
}
