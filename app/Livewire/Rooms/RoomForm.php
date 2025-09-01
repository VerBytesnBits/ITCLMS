<?php

namespace App\Livewire\Rooms;

use Livewire\Component;
use App\Models\Room;
use Illuminate\Validation\ValidationException;

class RoomForm extends Component
{
    public ?Room $room = null;

    public $roomId;
    public $name = '';
    public $description = '';
    public $status = 'active';

    public $showModal = false;

    protected $listeners = ['open-room-form' => 'open'];

    public function open($roomId = null)
    {
        $this->resetErrorBag();
        $this->roomId = $roomId;

        if ($roomId) {
            $this->room = Room::findOrFail($roomId);
            $this->name = $this->room->name;
            $this->description = $this->room->description;
            $this->status = $this->room->status;
        } else {
            $this->room = null;
            $this->name = '';
            $this->description = '';
            $this->status = 'active';
        }

        $this->dispatch('open-modal', modal: 'room-form');
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|unique:rooms,name,' . $this->roomId,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function save()
    {
        try {
            $validated = $this->validate();

            if ($this->room) {
                $this->room->update($validated);
                $this->dispatch('swal', toast: true, icon: 'success', title: 'Room updated successfully', timer: 3000);
                $this->dispatch('roomUpdated');
            } else {
                Room::create($validated);
                $this->dispatch('swal', toast: true, icon: 'success', title: 'Room created successfully', timer: 3000);
                $this->dispatch('roomCreated');
            }

            $this->dispatch('close-modal', modal: 'room-form');

        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
            $errors = $e->validator->errors()->all();
            $this->dispatch('swal', toast: true, icon: 'error', title: implode(' ', $errors), timer: 3000);
        }
    }

    public function render()
    {
        return view('livewire.rooms.room-form');
    }
}
