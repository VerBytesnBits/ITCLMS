<?php

namespace App\Livewire\Rooms;

use Livewire\Component;
use App\Models\Room;
use App\Models\User;

class AssignTechnician extends Component
{
    public ?Room $room = null;
    public $roomId;
    public $selectedTechnicianIds = [];

    public function mount($roomId)
    {
        $this->roomId = $roomId;
        $this->room = Room::findOrFail($roomId);

        // Prefill with currently assigned non-privileged users
        $this->selectedTechnicianIds = $this->room->users()
            ->wherePivotNotIn('role_in_room', ['lab_incharge', 'chairman'])
            ->pluck('users.id')
            ->toArray();
    }

    public function save()
    {
        $isLabIncharge = $this->room->users()
            ->wherePivot('role_in_room', 'lab_incharge')
            ->where('user_id', auth()->id())
            ->exists();

        if (!$isLabIncharge && !auth()->user()->hasRole('chairman')) {
            abort(403, 'Unauthorized');
        }

        // Get current non-privileged assigned users
        $currentIds = $this->room->users()
            ->wherePivotNotIn('role_in_room', ['lab_incharge', 'chairman'])
            ->pluck('users.id')
            ->toArray();

        $toAttach = array_diff($this->selectedTechnicianIds, $currentIds);
        $toDetach = array_diff($currentIds, $this->selectedTechnicianIds);

        // Detach removed users
        if (!empty($toDetach)) {
            $this->room->users()
                ->whereIn('user_id', $toDetach)
                ->detach();
        }

        // Attach new users with their actual role
        foreach ($toAttach as $id) {
            $user = User::with('roles')->find($id);
            $roleName = $user->roles->first()->name ?? 'intern';
            $this->room->users()->attach($id, ['role_in_room' => $roleName]);
        }

        $this->dispatch('swal', toast: true, icon: 'success', title: 'Assigned successfully');
        $this->dispatch('closeModal');
        $this->dispatch('roomUpdated');
    }

    public function render()
    {
        $excludedRoles = ['chairman',  'lab_incharge'];

        $technicianOptions = User::whereDoesntHave('roles', function ($q) use ($excludedRoles) {
                $q->whereIn('name', $excludedRoles);
            })
            ->with('roles')
            ->get()
            ->mapWithKeys(fn($user) => [
                $user->id => $user->name . ' - ' . ($user->roles->first()->name ?? 'No Role')
            ])
            ->toArray();

        return view('livewire.rooms.assign-technician', [
            'technicianOptions' => $technicianOptions,
        ]);
    }
}