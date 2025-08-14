<?php

namespace App\Livewire\Rooms;

use Livewire\Component;
use App\Models\Room;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RoomForm extends Component
{
    public ?Room $room = null;

    public $name = '';
    public $description = '';
    public $status = 'Available'; // default value
    public $lab_in_charge_id = null;

    public $labInChargeOptions;

    public function mount($roomId = null)
    {
        $this->labInChargeOptions = User::role('lab_incharge')->pluck('name', 'id')->toArray();

        if ($roomId) {
            $this->room = Room::findOrFail($roomId);
            $this->name = $this->room->name;
            $this->description = $this->room->description;
            $this->status = $this->room->status;
            $this->lab_in_charge_id = $this->room->lab_in_charge_id;
        }
    }



    protected function rules()
    {
        return [
            'name' => ['required', 'min:3', Rule::unique('rooms', 'name')->ignore($this->room?->id)],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['Available', 'Unavailable'])], // updated ENUM
            'lab_in_charge_id' => [
                'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $alreadyAssigned = Room::where('lab_in_charge_id', $value)
                            ->when($this->room, fn($query) => $query->where('id', '!=', $this->room->id))
                            ->exists();

                        if ($alreadyAssigned) {
                            $fail('This user is already assigned as a Lab In-Charge to another room.');
                        }
                    }
                },
            ],
        ];
    }


    public function save()
    {
        try {
            $validated = $this->validate();
            $isNew = false;

            // Create or update the room
            if ($this->room) {
                $oldLabInChargeId = $this->room->lab_in_charge_id; //  Track old
                $this->room->update($validated);
            } else {
                $this->room = Room::create($validated);
                $oldLabInChargeId = null;
                $isNew = true;
            }

            // Enforce 1-to-1 lab in charge rule
            if ($this->lab_in_charge_id) {
                // Unassign this lab in charge from any other room
                Room::where('lab_in_charge_id', $this->lab_in_charge_id)
                    ->where('id', '!=', $this->room->id)
                    ->update(['lab_in_charge_id' => null]);

                // Update the room's lab in charge
                $this->room->lab_in_charge_id = $this->lab_in_charge_id;
                $this->room->save();

                // Assign this user to the room
                User::where('id', $this->lab_in_charge_id)
                    ->update(['assigned_room_id' => $this->room->id]);
            }

            //  If lab in charge was changed, remove assigned_room_id from old user
            if (
                isset($oldLabInChargeId)
                && $oldLabInChargeId !== $this->lab_in_charge_id
            ) {
                User::where('id', $oldLabInChargeId)
                    ->update(['assigned_room_id' => null]);
            }

            $message = $isNew ? 'Room created successfully' : 'Room updated successfully';

            $this->dispatch('swal', toast: true, icon: 'success', title: $message, timer: 3000);
            $this->dispatch($isNew ? 'roomCreated' : 'roomUpdated');
            $this->dispatch('closeModal');
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $this->dispatch('swal', toast: true, icon: 'error', title: implode(' ', $errors), timer: 3000);
            $this->setErrorBag($e->validator->errors());
        }
    }



    public function render()
    {
        return view('livewire.rooms.room-form');
    }
}
