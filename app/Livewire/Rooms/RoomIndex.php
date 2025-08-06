<?php

namespace App\Livewire\Rooms;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RoomIndex extends Component
{
    #[Url(as: 'modal')]
    public ?string $modal = null;

    #[Url(as: 'id')]
    public ?int $id = null;

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
        $user = Auth::user();


        if (!$user) {
            $this->rooms = collect();
            return;
        }

        if ($user->hasRole('lab_incharge')) {
            $this->rooms = Room::with('labInCharge')
                ->where('lab_in_charge_id', $user->id)
                ->latest()
                ->get();
        } elseif ($user->hasRole('chairman')) {
            $this->rooms = Room::with('labInCharge')->latest()->get();
        } else {
            $this->rooms = collect();
        }
    }

    public function render()
    {
        return view('livewire.rooms.room-index', [
            'rooms' => $this->rooms, // use filtered $this->rooms
        ]);
    }
}
