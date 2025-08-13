<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SystemUnit;

class PartForm extends Component
{
    public ?int $unitId = null;
    public $type;
    public $fields = [];
    public $partId = null;
    public ?SystemUnit $unit = null;

    public function mount(?int $unitId, string $type, $partId = null)
    {
        if ($unitId) {
            $this->unit = SystemUnit::findOrFail($unitId);
        }

        $this->type = $type;
        $this->fields = $this->defaultFields($type);

        if ($partId && $this->unit) {
            $modelClass = $this->modelMap()[$type];
            $part = $modelClass::findOrFail($partId);
            foreach ($this->fields as $field => $value) {
                $this->fields[$field] = $part->$field ?? '';
            }
            $this->partId = $partId;
        }
    }

    public function save()
    {
        $this->validate($this->validationRules($this->type));

        // If editing an existing unit → save directly to DB
        if ($this->unitId) {
            $modelClass = $this->modelMap()[$this->type];
            $this->fields['system_unit_id'] = $this->unitId;

            if ($this->partId) {
                $modelClass::findOrFail($this->partId)->update($this->fields);
            } else {
                $modelClass::create($this->fields);
            }

            $this->dispatch('part-saved', type: $this->type);
            return;
        }

        // If creating a new unit → attach to in-memory selection
        $tempFields = $this->fields;
        $tempFields['temp_id'] = uniqid('temp_'); // unique for UI tracking
        $tempFields['type'] = $this->type;

        $this->dispatch('part-temp-added', $tempFields);
    }



    private function modelMap()
    {
        return [
            'processors' => \App\Models\Processor::class,
            'motherboards' => \App\Models\Motherboard::class,
            'memories' => \App\Models\Memory::class,
            'graphics_cards' => \App\Models\GraphicsCard::class,
            'power_supplies' => \App\Models\PowerSupply::class,
            'computer_cases' => \App\Models\ComputerCase::class,
            'cpu_coolers' => \App\Models\CpuCooler::class,
            'hard_disk_drives' => \App\Models\HardDiskDrive::class,
            'sata_ssds' => \App\Models\SataSsd::class,
            'm2_ssds' => \App\Models\M2Ssd::class,
            'keyboards' => \App\Models\Keyboard::class,
            'mice' => \App\Models\Mouse::class,
            'headsets' => \App\Models\Headset::class,
            'displays' => \App\Models\Display::class,
            'speakers' => \App\Models\Speaker::class,
            'web_digital_cameras' => \App\Models\WebDigitalCamera::class,
        ];
    }


    private function defaultFields($type)
    {
        $common = [
            'brand' => '',
            'model' => '',
            'serial_number' => '',
            'status' => 'Working',
            'date_purchased' => null
        ];

        $map = [
            'processors' => $common + [
                'generation' => '',
                'cores' => null,
                'threads' => null,
                'base_clock' => null,
                'boost_clock' => null,
            ],
            'cpu_coolers' => $common + [
                'type' => '',
                'fan_size' => ''
            ],
            'motherboards' => $common + [
                'form_factor' => '',
                'chipset' => '',
                'socket' => ''
            ],
            'memories' => $common + [
                'type' => '',
                'capacity' => '',
                'speed' => ''
            ],
            'graphics_cards' => $common + [
                'chipset' => '',
                'memory_size' => '',
                'memory_type' => ''
            ],
            'm2_ssds' => $common + [
                'type' => '',
                'capacity' => '',
                'interface' => ''
            ],
            'sata_ssds' => $common + [
                'type' => '',
                'capacity' => '',
                'interface' => 'SATA'
            ],
            'hard_disk_drives' => $common + [
                'capacity' => '',
                'type' => 'HDD',
                'rpm' => '',
                'interface' => ''
            ],
            'power_supplies' => $common + [
                'wattage' => '',
                'efficiency_rating' => '',
                'modular' => ''
            ],
            'computer_cases' => $common + [
                'form_factor' => '',
                'color' => ''
            ],
            'displays' => $common + [
                'resolution' => '',
                'size_inches' => '',
                'panel_type' => ''
            ],
            'keyboards' => $common + [
                'connection_type' => ''
            ],
            'mice' => $common + [
                'connection_type' => ''
            ],
            'headsets' => $common + [
                'connection_type' => ''
            ],
            'speakers' => $common + [
                'connection_type' => ''
            ],
            'web_digital_cameras' => $common + [
                'resolution' => '',
                'connection_type' => ''
            ],
        ];

        return $map[$type] ?? $common;
    }

    private function validationRules($type)
    {
        $common = [
            'fields.brand' => 'required|string|max:255',
            'fields.model' => 'required|string|max:255',
            'fields.serial_number' => 'nullable|string|max:255',
            'fields.status' => 'required|in:Working,Under Maintenance,Decommissioned',
            'fields.date_purchased' => 'nullable|date',
        ];

        $map = [
            'processors' => $common + [
                'fields.generation' => 'nullable|string|max:255',
                'fields.cores' => 'nullable|integer|min:1',
                'fields.threads' => 'nullable|integer|min:1',
                'fields.base_clock' => 'nullable|string|max:255',
                'fields.boost_clock' => 'nullable|string|max:255',
            ],
            'cpu_coolers' => $common + [
                'fields.type' => 'nullable|string|max:255',
                'fields.fan_size' => 'nullable|string|max:255',
            ],
            'motherboards' => $common + [
                'fields.form_factor' => 'nullable|string|max:255',
                'fields.chipset' => 'nullable|string|max:255',
                'fields.socket' => 'nullable|string|max:255',
            ],
            'memories' => $common + [
                'fields.type' => 'nullable|string|max:255',
                'fields.capacity' => 'nullable|integer|min:1',
                'fields.speed' => 'nullable|integer|min:1',
            ],
            'graphics_cards' => $common + [
                'fields.chipset' => 'nullable|string|max:255',
                'fields.memory_size' => 'nullable|integer|min:1',
                'fields.memory_type' => 'nullable|string|max:255',
            ],
            'm2_ssds' => $common + [
                'fields.type' => 'nullable|string|max:255',
                'fields.capacity' => 'nullable|integer|min:1',
                'fields.interface' => 'nullable|string|max:255',
            ],
            'sata_ssds' => $common + [
                'fields.type' => 'nullable|string|max:255',
                'fields.capacity' => 'nullable|integer|min:1',
                'fields.interface' => 'nullable|string|max:255',
            ],
            'hard_disk_drives' => $common + [
                'fields.capacity' => 'nullable|integer|min:1',
                'fields.type' => 'nullable|string|max:255',
                'fields.rpm' => 'nullable|string|max:255',
                'fields.interface' => 'nullable|string|max:255',
            ],
            'power_supplies' => $common + [
                'fields.wattage' => 'nullable|integer|min:1',
                'fields.efficiency_rating' => 'nullable|string|max:255',
                'fields.modular' => 'nullable|string|max:255',
            ],
            'computer_cases' => $common + [
                'fields.form_factor' => 'nullable|string|max:255',
                'fields.color' => 'nullable|string|max:255',
            ],
            'displays' => $common + [
                'fields.resolution' => 'nullable|string|max:255',
                'fields.size_inches' => 'nullable|integer|min:1',
                'fields.panel_type' => 'nullable|string|max:255',
            ],
            'keyboards' => $common + [
                'fields.connection_type' => 'nullable|string|max:255',
            ],
            'mice' => $common + [
                'fields.connection_type' => 'nullable|string|max:255',
            ],
            'headsets' => $common + [
                'fields.connection_type' => 'nullable|string|max:255',
            ],
            'speakers' => $common + [
                'fields.connection_type' => 'nullable|string|max:255',
            ],
            'web_digital_cameras' => $common + [
                'fields.resolution' => 'nullable|string|max:255',
                'fields.connection_type' => 'nullable|string|max:255',
            ],
        ];

        return $map[$type] ?? $common;
    }


    public function render()
    {
        return view('livewire.part-form');
    }
}
