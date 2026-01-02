<?php

namespace App\Livewire\Peripherals;

use Livewire\Component;
use App\Models\Peripheral;
use App\Models\SystemUnit;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PeripheralForm extends Component
{
    public ?Peripheral $peripheral = null;

    public $peripheralId = null;
    public $system_unit_id;
    public $room_id;
    public $serial_number;
    public $brand;
    public $model;
    public $color;
    public $type;
    public $condition = 'Good';
    public $status = 'Available';
    public $purchase_date;
    public $warranty_period_months;
    public $retirement_action;
    public $retirement_notes;
    public $retired_at;

    public $modalMode = 'create';
    public $multiple = false;   // add-many mode
    public $quantity = 1;       // how many to add

    //uncommon fields
    public $screen_size;   // Monitor
    public $switch_type;   // Keyboard
    public $dpi;           // Mouse
    public $printer_type;  // Printer
    public $wattage;       // Speaker
    public $lumens;        // Projector
    public $resolution;    // Webcam
    public $capacity_va;   // AVR & UPS


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
            'purchase_date' => ['nullable', 'date'],
            'warranty_period_months' => ['nullable', 'integer', 'min:0'],
            'retirement_action' => ['nullable', 'string'],
            'retirement_notes' => ['nullable', 'string'],
            'retired_at' => ['nullable', 'date'],

            // Peripheral-specific dynamic fields
            'screen_size' => ['nullable', 'string'],   // Monitor
            'switch_type' => ['nullable', 'string'],   // Keyboard
            'dpi' => ['nullable', 'integer'],          // Mouse
            'printer_type' => ['nullable', 'string'],  // Printer
            'wattage' => ['nullable', 'string'],       // Speaker
          
            'resolution' => ['nullable', 'string'],    // Webcam
            'capacity_va' => ['nullable', 'string'],   // AVR / UPS
        ];
    }

    /**
     * Centralize form data mapping
     */
    protected function formData(): array
    {
        return $this->only([
            'system_unit_id',
            'room_id',
            'serial_number',
            'brand',
            'model',
            'color',
            'type',
            'condition',
            'status',
            'purchase_date',
            'warranty_period_months',
            'retirement_action',
            'retirement_notes',
            'retired_at',

            // Peripheral-specific fields
            'screen_size',
            'switch_type',
            'dpi',
            'printer_type',
            'wattage',
          
            'resolution',
            'capacity_va',
        ]);
    }


    public function mount($id = null)
    {
        if ($id) {
            $this->peripheral = Peripheral::findOrFail($id);
            $this->peripheralId = $this->peripheral->id;
            $this->modalMode = 'edit';

            $this->fill($this->peripheral->only(array_keys($this->formData())));

            $this->purchase_date = $this->peripheral->purchase_date
                ? Carbon::parse($this->peripheral->purchase_date)->format('Y-m-d')
                : null;

            $this->retired_at = $this->peripheral->retired_at
                ? Carbon::parse($this->peripheral->retired_at)->format('Y-m-d H:i:s')
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

        if ($this->modalMode === 'create') {
            // Create the peripheral
            $peripheral = Peripheral::create($data);

            // Get the serial number
            $serialNumber = $peripheral->serial_number ?? 'N/A';
            $title = "Peripheral Added: {$peripheral->type} (SN: $serialNumber)";
            $event = 'peripheralCreated';
        } else {
            // Update the peripheral
            $this->peripheral->update($data);

            $serialNumber = $this->peripheral->serial_number ?? 'N/A';
            $title = "Peripheral Updated: {$this->peripheral->type} (SN: $serialNumber)";
            $event = 'peripheralUpdated';
        }

        $this->dispatch($event);
        $this->dispatch('swal', toast: true, icon: 'success', title: $title, timer: 3000);
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
