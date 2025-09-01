<?php
namespace App\Livewire\Rooms;

use Livewire\Component;
use App\Models\Room;
use App\Models\User;

class AssignLabIncharge extends Component
{
    public ?Room $room = null;  // Make nullable
    public $user_id;

    // Use mount to initialize the room
    public function mount($roomId)
    {
        $this->room = Room::findOrFail($roomId);
    }

    public function save()
    {
        $this->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Assign the lab in-charge
        $this->room->users()->syncWithoutDetaching([
            $this->user_id => ['role_in_room' => 'lab_incharge']
        ]);

        $this->dispatch('closeModal');
        $this->dispatch('roomUpdated'); // refresh parent
    }

    public function render()
    {
        return view('livewire.rooms.assign-lab-incharge', [
            'users' => User::role('lab_incharge')->get(),
        ]);
    }
}
