<?php

namespace App\Livewire\ComponentsPart;

use Livewire\Component;
use App\Models\ComponentParts;
use App\Models\SystemUnit;
use Carbon\Carbon;
use Illuminate\Support\Str;
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
    public $multiple = false;   // for checkbox
    public $quantity = 1;       // for number input

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
        ];
    }

    /**
     * Get all fillable attributes once
     */
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
        ]);
    }

    public function mount($id = null)
    {
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


    // ...

    public function updatedPart($value)
    {
        if ($this->modalMode === 'create' && !$this->multiple) {
            $this->serial_number = $this->generateSerial($value);
        } else {
            // Clear serial preview when multiple mode is on
            $this->serial_number = null;
        }
    }

    public function updatedMultiple($value)
    {
        if ($value) {
            // If user turns on "Add more", clear the pre-generated serial
            $this->serial_number = null;
        } else {
            // If user unchecks "Add more", regenerate serial for the selected part
            if ($this->part) {
                $this->serial_number = $this->generateSerial($this->part);
            }
        }
    }

    private function generateSerial(string $prefix): string
    {
        do {
            // Generate a random serial (you can adjust the format)
            $serial = strtoupper($prefix) . '-' . strtoupper(Str::random(5)) . rand(1000, 9999);
        } while (ComponentParts::where('serial_number', $serial)->exists());

        return $serial;
    }

    public function save()
    {
        if ($this->modalMode === 'create' && $this->multiple) {
            $this->serial_number = $this->generateSerial($this->part);
        }
        $this->validate();

        $this->validate();

        $data = $this->formData();
        if (!empty($data['warranty_period_months'])) {
            $data['warranty_period_months'] = (int) $data['warranty_period_months'];
        }
        if ($this->modalMode === 'create') {
            $count = $this->multiple ? $this->quantity : 1;

            for ($i = 0; $i < $count; $i++) {
                // Only generate serials in loop if multiple
                $data['serial_number'] = $this->multiple
                    ? $this->generateSerial($this->part)
                    : $this->serial_number;

                ComponentParts::create($data);
            }

            $title = $count > 1 ? "{$count} Components created!" : "Component created!";
            $event = 'componentCreated';
        } else {
            $this->component->update($data);
            $title = 'Component updated!';
            $event = 'componentUpdated';
        }

        $this->dispatch($event);
        $this->dispatch('swal', toast: true, icon: 'success', title: $title, timer: 3000);
        $this->dispatch('closeModal');
    }




    public function render()
    {
        return view('livewire.components-part.form', [
            'systemUnits' => SystemUnit::all(),
        ]);
    }
}
