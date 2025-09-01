<?php

namespace App\Livewire\ComponentsPart;

use Livewire\Component;
use App\Models\ComponentParts;
use App\Models\SystemUnit;

class Form extends Component
{
    public ?ComponentParts $component = null;

    public $componentId = null; // 
    public $system_unit_id;
    public $serial_number;
    public $brand;
    public $model;
    public $capacity;
    public $speed;
    public $type;
    public $part;
    public $condition = 'Good';
    public $status = 'Available';
    public $warranty;

    public $modalMode = 'create';

    protected function rules()
    {
        return [
            'system_unit_id' => ['nullable', 'exists:system_units,id'],
            'serial_number' => ['required', 'string', 'unique:component_parts,serial_number,' . $this->componentId],
            'brand' => ['nullable', 'string'],
            'model' => ['nullable', 'string'],
            'capacity' => ['nullable', 'string'],
            'speed' => ['nullable', 'string'],
            'type' => ['nullable', 'string'], // e.g., DDR4, SSD, SATA, etc.
            'part' => ['required', 'string'], // e.g., Memory, Drive, GPU, PSU, Case
            'condition' => ['required', 'in:Excellent,Good,Fair,Poor'],
            'status' => ['required', 'in:Available,In Use,Defective,Under Maintenance'],
            'warranty' => ['nullable', 'date'],
        ];
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->component = ComponentParts::findOrFail($id);
            $this->componentId = $this->component->id;
            $this->modalMode = 'edit';

            $this->fill($this->component->only([
                'system_unit_id',
                'serial_number',
                'brand',
                'model',
                'capacity',
                'speed',
                'type',
                'part',
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
            ComponentParts::create($this->only([
                'system_unit_id',
                'serial_number',
                'brand',
                'model',
                'capacity',
                'speed',
                'type',
                'part',
                'condition',
                'status',
                'warranty',
            ]));
            $this->dispatch('componentCreated');
            $this->dispatch('swal', toast: true, icon: 'success', title: 'Component created!', timer: 3000);
        } else {
            $this->component->update($this->only([
                'system_unit_id',
                'serial_number',
                'brand',
                'model',
                'capacity',
                'speed',
                'type',
                'part',
                'condition',
                'status',
                'warranty',
            ]));
            $this->dispatch('componentUpdated');
            $this->dispatch('swal', toast: true, icon: 'success', title: 'Component updated!', timer: 3000);
        }

        $this->dispatch('closeModal');
    }

    public function render()
    {
        return view('livewire.components-part.form', [
            'systemUnits' => SystemUnit::all(),
        ]);
    }
}
