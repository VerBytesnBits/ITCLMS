<?php

namespace App\Livewire\ComponentsPart;

use App\Livewire\ComponentPartsTable;
use Livewire\Component;
use App\Models\ComponentParts;
use App\Models\SystemUnit;
use Carbon\Carbon;

class Form extends Component
{
    public ?ComponentParts $component = null;

    public $componentId = null;
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
    public $purchase_date;
    public $warranty_period_months;

    public $modalMode = 'create';
    public $multiple = false;  
    public $quantity = 1;       
    public $room_id = null;
    public $embedded = false; 

    protected function rules()
    {
        return [
            'system_unit_id' => ['nullable', 'exists:system_units,id'],
            'serial_number' => ['required', 'string', 'unique:component_parts,serial_number,' . $this->componentId],
            'brand' => ['nullable', 'string'],
            'model' => ['nullable', 'string'],
            'capacity' => ['nullable', 'string'],
            'speed' => ['nullable', 'string'],
            'type' => ['nullable', 'string'],
            'part' => ['required', 'string'],
            'condition' => ['required', 'in:Excellent,Good,Fair,Poor'],
            'status' => ['required', 'in:Available,In Use,Defective,Under Maintenance'],
            'purchase_date' => ['nullable', 'date'],
            'warranty_period_months' => ['nullable', 'integer', 'min:0'],
            'room_id' => ['nullable', 'exists:rooms,id'],
        ];
    }

  
    protected function formData(): array
    {
        return $this->only([
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
            'purchase_date',
            'warranty_period_months',
            'room_id',
        ]);
    }

    public $rooms = [];
    public function mount($id = null)
    {
        $this->rooms = \App\Models\Room::orderBy('name')->get();
        if ($id) {
            $this->component = ComponentParts::findOrFail($id);
            $this->componentId = $this->component->id;
            $this->modalMode = 'edit';
            $this->fill($this->component->only(array_keys($this->formData())));

            $this->purchase_date = $this->component->purchase_date
                ? Carbon::parse($this->component->purchase_date)->format('Y-m-d')
                : null;

        }
    }



    public function save()
    {
        $this->validate();

        $data = $this->formData();

        if (!empty($data['warranty_period_months'])) {
            $data['warranty_period_months'] = (int) $data['warranty_period_months'];
        }

        
        if ($this->embedded) {

            $this->dispatch(
                'tempComponentAdded',
                component: $data
            );

            $this->dispatch(
                'swal',
                toast: true,
                icon: 'success',
                title: 'Component temporarily added to Unit'
            );

            $this->reset([
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
                'purchase_date',
                'warranty_period_months',
                'room_id',
                'multiple',
                'quantity',
            ]);

            return;
        }

        if ($this->modalMode === 'create') {

            ComponentParts::create($data);

            $title = "Component Added!";
            $event = 'componentCreated';

        } else {

            $this->component->update($data);

            $title = 'Component updated!';
            $event = 'componentUpdated';
        }

        $this->dispatch($event);
        $this->dispatch('swal', toast: true, icon: 'success', title: $title, timer: 3000);
        $this->dispatch('closeModal');
         $this->dispatch('refresh-part-table')
            ->to(ComponentPartsTable::class);
    }






    public function render()
    {
        return view('livewire.components-part.form', [
            'systemUnits' => SystemUnit::all(),
        ]);
    }
}
