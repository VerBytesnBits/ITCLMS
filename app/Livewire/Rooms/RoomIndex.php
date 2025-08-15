<?php

namespace App\Livewire\Rooms;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;

class RoomIndex extends Component
{
    #[Url(as: 'modal')]
    public ?string $modal = null;

    #[Url(as: 'id')]
    public ?int $id = null;

    public $rooms;
    public $viewRoomId = null;

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

        $baseQuery = $this->rooms = Room::with([
            'labInCharge',
            'systemUnits.processor',
            'systemUnits.motherboard',
            'systemUnits.memories',
            'systemUnits.graphicsCards',
            'systemUnits.m2Ssds',
            'systemUnits.sataSsds',
            'systemUnits.hardDiskDrives',
            'systemUnits.powerSupply',
            'systemUnits.computerCase',
            'systemUnits.cpuCooler',
            'systemUnits.monitor',
            'systemUnits.keyboard',
            'systemUnits.mouse',
            'systemUnits.headset',
            'systemUnits.speaker',
            'systemUnits.webCamera',
        ])->latest()->get();

        if ($user->hasRole('lab_incharge')) {
            $baseQuery->where('lab_in_charge_id', $user->id);
        }

        // $this->rooms = $baseQuery->get();
    }

    public function viewRoomUnits($roomId)
    {
        $this->viewRoomId = $roomId;
    }

    public function closeUnitsModal()
    {
        $this->viewRoomId = null;
    }

    public function render()
    {
        return view('livewire.rooms.room-index', [
            'rooms' => $this->rooms,
        ]);
    }
}
