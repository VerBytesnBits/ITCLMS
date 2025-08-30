<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;
use App\Models\Room;

class UnitForm extends Component
{
    public $show = false;
    public $unitId, $name, $serial_number, $status = 'Available', $room_id;

    protected $rules = [
        'name' => 'required|string|max:100',
        'serial_number' => 'nullable|string|unique:system_units,serial_number',
        'status' => 'required|string',
        'room_id' => 'required|exists:rooms,id',
    ];

    protected $listeners = ['open-unit-form' => 'open'];

    public function open()
    {
        $this->resetValidation();
        $this->reset(['unitId', 'name', 'serial_number', 'status', 'room_id']);
        $this->status = 'Available';
        $this->show = true;
    }

    public function save()
    {
        $this->validate();

        SystemUnit::updateOrCreate(
            ['id' => $this->unitId],
            [
                'name' => $this->name,
                'serial_number' => $this->serial_number,
                'status' => $this->status,
                'room_id' => $this->room_id,
            ]
        );

        $this->dispatch('unit-saved');
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.system-units.unit-form', [
            'rooms' => Room::all(),
        ]);
    }
}
