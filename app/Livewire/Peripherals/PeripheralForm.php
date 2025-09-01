<?php

namespace App\Livewire\Peripherals;

use Livewire\Component;
use App\Models\Peripheral;
use App\Models\SystemUnit;
use App\Models\Room;

class PeripheralForm extends Component
{
    public ?Peripheral $peripheral = null;

    public $peripheralId = null; // 🔹 store ID
    public $system_unit_id;
    public $room_id;
    public $serial_number;
    public $brand;
    public $model;
    public $color;
    public $type;
    public $condition = 'Good';
    public $status = 'Available';
    public $warranty;

    public $modalMode = 'create';

    protected function rules()
    {
        return [
            'system_unit_id' => ['nullable', 'exists:system_units,id'],
            'room_id' => ['nullable', 'exists:rooms,id'],
            'serial_number' => ['required', 'string', 'unique:peripherals,serial_number,' . $this->peripheralId],
            'brand' => ['nullable', 'string'],
            'model' => ['nullable', 'string'],
            'color' => ['nullable', 'string'],
            'type' => ['required', 'string'],
            'condition' => ['required', 'in:Excellent,Good,Fair,Poor'],
            'status' => ['required', 'in:Available,In Use,Defective,Under Maintenance'],
            'warranty' => ['nullable', 'date'],
        ];
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->peripheral = Peripheral::findOrFail($id);
            $this->peripheralId = $this->peripheral->id;
            $this->modalMode = 'edit';

            $this->fill($this->peripheral->only([
                'system_unit_id',
                'room_id',
                'serial_number',
                'brand',
                'model',
                'color',
                'type',
                'condition',
                'status',
                'warranty',
            ]));
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->modalMode === 'create') {
            Peripheral::create($this->only([
                'system_unit_id',
                'room_id',
                'serial_number',
                'brand',
                'model',
                'color',
                'type',
                'condition',
                'status',
                'warranty',
            ]));
            $this->dispatch('peripheralCreated');
            $this->dispatch('swal', toast: true, icon: 'success', title: 'Peripheral created!', timer: 3000);
        } else {
            $this->peripheral->update($this->only([
                'system_unit_id',
                'room_id',
                'serial_number',
                'brand',
                'model',
                'color',
                'type',
                'condition',
                'status',
                'warranty',
            ]));
            $this->dispatch('peripheralUpdated');
            $this->dispatch('swal', toast: true, icon: 'success', title: 'Peripheral updated!', timer: 3000);
        }

        $this->dispatch('closeModal');
    }

    public function render()
    {
        return view('livewire.peripherals.peripheral-form', [
            'systemUnits' => SystemUnit::all(),
            'rooms' => Room::all(),
        ]);
    }
}
