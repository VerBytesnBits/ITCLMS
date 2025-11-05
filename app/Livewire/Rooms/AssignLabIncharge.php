<?php
namespace App\Livewire\Rooms;

use Livewire\Component;
use App\Models\Room;
use App\Models\User;

class AssignLabIncharge extends Component
{
    public ?Room $room = null;
    public array $user_ids = []; // <-- use array for multiple selection

    public function mount($roomId)
    {
        $this->room = Room::findOrFail($roomId);

        // Pre-fill already assigned lab in-charges if any
        $this->user_ids = $this->room->users()
            ->wherePivot('role_in_room', 'lab_incharge')
            ->pluck('users.id')
            ->toArray();
    }

    public function save()
    {
        $this->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        // Sync all selected lab in-charges for this room
        $syncData = collect($this->user_ids)->mapWithKeys(fn($id) => [
            $id => ['role_in_room' => 'lab_incharge']
        ])->toArray();

        $this->room->users()->syncWithoutDetaching($syncData);
        $this->dispatch('swal', toast: true, icon: 'success', title: 'Lab Incharge(s) assigned successfully');
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
