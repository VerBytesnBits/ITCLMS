<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;
use App\Models\Room;
use Illuminate\Validation\Rule;

class UnitForm extends Component
{
    public $show = false;
    public $mode = 'create'; // create | edit
    public $unitId;

    public $name;
    public $serial_number;
    public $status = 'Operational';
    public $condition = 'Operational';
    public $room_id;

    protected $listeners = [
        'open-unit-create' => 'create',
        'open-unit-edit' => 'edit',
        'closeModal' => 'close',
    ];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'serial_number' => [
                'nullable',
                'string',
                Rule::unique('system_units', 'serial_number')->ignore($this->unitId),
            ],
            'status' => 'required|string',
          
            'room_id' => 'required|exists:rooms,id',
        ];
    }

    /** Open Create Mode */
    public function create()
    {
        $this->resetValidation();
        $this->reset(['unitId', 'name', 'serial_number', 'status','room_id']);
        $this->mode = 'create';
        $this->show = true;
    }

    /** Open Edit Mode */
    public function edit(SystemUnit $unit)
    {
        $this->resetValidation();

        $this->unitId = $unit->id;
        $this->name = $unit->name;
        $this->serial_number = $unit->serial_number;
        $this->status = $unit->status;
        $this->condition = $unit->condition;
        $this->room_id = $unit->room_id;

        $this->mode = 'edit';
        $this->show = true;
    }

    /** Save / Update Unit */
    public function save()
    {
        $this->validate();

        if ($this->mode === 'create') {
            $unit = SystemUnit::create([
                'name' => $this->name,
                'serial_number' => $this->serial_number,
                'status' => $this->status,
               
                'room_id' => $this->room_id,
            ]);
        } else {
            $unit = SystemUnit::findOrFail($this->unitId);
            $unit->update([
                'name' => $this->name,
                'serial_number' => $this->serial_number,
                'status' => $this->status,
                
                'room_id' => $this->room_id,
            ]);
        }

        $this->dispatch('unit-saved', $unit->id);
        $this->dispatch("closeModal");
    }
    //    public function close()
    // {
    //     $this->show = false;
    // }

    public function render()
    {
        return view('livewire.system-units.unit-form', [
            'rooms' => Room::all(),
        ]);
    }
}
