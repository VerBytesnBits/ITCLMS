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
        // Normalize $type to match keys in modelMap() and defaultFields()
        $typePlural = rtrim($type, 's') . 's';
        $this->type = $typePlural;

        if ($unitId) {
            $this->unit = SystemUnit::findOrFail($unitId);
            $this->unitId = $unitId;
        }

        // Set default fields
        $this->fields = $this->defaultFields($this->type);

        // If editing, prefill fields with DB values
        if ($partId && $this->unit) {
            $modelClass = $this->modelMap()[$this->type] ?? null;
            if ($modelClass) {
                $part = $modelClass::findOrFail($partId);
                foreach ($this->fields as $field => $value) {
                    $this->fields[$field] = $part->$field ?? $value;
                }
                $this->partId = $partId;
            }
        }
    }


    public function save()
    {
        $this->validate($this->validationRules($this->type));

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

        $tempFields = $this->fields;
        $tempFields['temp_id'] = uniqid('temp_');
        $tempFields['type'] = $this->type;

        $this->dispatch('part-temp-added', $tempFields);
    }

    private function modelMap()
    {
        return [
            'processors' => \App\Models\Processor::class,
            'motherboards' => \App\Models\Motherboard::class,
            'memories' => \App\Models\Memory::class,
            'graphicsCards' => \App\Models\GraphicsCard::class,
            'powerSupplies' => \App\Models\PowerSupply::class,
            'computerCase' => \App\Models\ComputerCase::class,
            'cpuCoolers' => \App\Models\CpuCooler::class,
            'hardDiskDrives' => \App\Models\HardDiskDrive::class,
            'sataSsds' => \App\Models\SataSsd::class,
            'm2Ssds' => \App\Models\M2Ssd::class,
            'keyboards' => \App\Models\Keyboard::class,
            'mice' => \App\Models\Mouse::class,
            'headsets' => \App\Models\Headset::class,
            'monitor' => \App\Models\Display::class,
            'speakers' => \App\Models\Speaker::class,
            'webDigitalCameras' => \App\Models\WebDigitalCamera::class,
        ];
    }

    private function defaultFields($type)
    {
        $common = [
            'brand' => '',
            'model' => '',
            'serial_number' => '',
            'status' => 'Operational', // default per new dictionary
            'condition' => 'New',      // new default condition
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
            'cpuCoolers' => $common + [
                'type' => '',
                'fan_size' => null
            ],
            'motherboards' => $common + [
                'form_factor' => '',
                'chipset' => '',
                'socket' => ''
            ],
            'memories' => $common + [
                'type' => '',
                'capacity' => '',
                'speed' => null
            ],
            'graphicsCards' => $common + [
                'chipset' => '',
                'memory_size' => null,
                'memory_type' => ''
            ],
            'm2Ssds' => $common + [
                'type' => '',
                'capacity' => '',
                'interface' => ''
            ],
            'sataSsds' => $common + [
                'type' => '',
                'capacity' => '',
                'interface' => 'SATA'
            ],
            'hardDiskDrives' => $common + [
                'capacity' => '',
                'type' => 'HDD',
                'rpm' => null,
                'interface' => ''
            ],
            'powerSupplies' => $common + [
                'wattage' => null,
                'efficiency_rating' => '',
                'modular' => null
            ],
            'computerCase' => $common + [
                'form_factor' => '',
                'color' => ''
            ],
            'monitor' => $common + [
                'resolution' => '',
                'size_inches' => null,
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
            'webDigitalCameras' => $common + [
                'resolution' => '',
                'connection_type' => ''
            ],
        ];

        return $map[$type] ?? $common;
    }

    private function validationRules($type)
    {
        $statusEnum = 'required|in:Operational,Needs Repair,Non-operational';
        $conditionEnum = 'required|in:New,Excellent,Good,Fair,Poor,Defective';

        $common = [
            'fields.brand' => 'required|string|max:255',
            'fields.model' => 'required|string|max:255',
            'fields.serial_number' => 'nullable|string|max:255',
            'fields.status' => $statusEnum,
            'fields.condition' => $conditionEnum,
            'fields.date_purchased' => 'nullable|date',
        ];

        $map = [
            'processors' => $common + [
                'fields.generation' => 'nullable|string|max:255',
                'fields.cores' => 'nullable|integer|min:1',
                'fields.threads' => 'nullable|integer|min:1',
                'fields.base_clock' => 'nullable|numeric',
                'fields.boost_clock' => 'nullable|numeric',
            ],
            'cpuCoolers' => $common + [
                'fields.type' => 'nullable|string|max:255',
                'fields.fan_size' => 'nullable|integer|min:1',
            ],
            'motherboards' => $common + [
                'fields.form_factor' => 'nullable|string|max:255',
                'fields.chipset' => 'nullable|string|max:255',
                'fields.socket' => 'nullable|string|max:255',
            ],
            'memories' => $common + [
                'fields.type' => 'nullable|string|max:255',
                'fields.capacity' => 'nullable|string|max:255',
                'fields.speed' => 'nullable|integer|min:1',
            ],
            'graphicsCards' => $common + [
                'fields.chipset' => 'nullable|string|max:255',
                'fields.memory_size' => 'nullable|integer|min:1',
                'fields.memory_type' => 'nullable|string|max:255',
            ],
            'm2Ssds' => $common + [
                'fields.type' => 'nullable|string|max:255',
                'fields.capacity' => 'nullable|string|max:255',
                'fields.interface' => 'nullable|string|max:255',
            ],
            'sataSsds' => $common + [
                'fields.type' => 'nullable|string|max:255',
                'fields.capacity' => 'nullable|string|max:255',
                'fields.interface' => 'nullable|string|max:255',
            ],
            'hardDiskDrives' => $common + [
                'fields.capacity' => 'nullable|string|max:255',
                'fields.type' => 'nullable|string|max:255',
                'fields.rpm' => 'nullable|integer|min:1',
                'fields.interface' => 'nullable|string|max:255',
            ],
            'powerSupplies' => $common + [
                'fields.wattage' => 'nullable|integer|min:1',
                'fields.efficiency_rating' => 'nullable|string|max:255',
                'fields.modular' => 'nullable|boolean',
            ],
            'computerCase' => $common + [
                'fields.form_factor' => 'nullable|string|max:255',
                'fields.color' => 'nullable|string|max:255',
            ],
            'monitor' => $common + [
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
            'webDigitalCameras' => $common + [
                'fields.resolution' => 'nullable|string|max:255',
                'fields.connection_type' => 'nullable|string|max:255',
            ],
        ];

        return $map[$type] ?? $common;
    }


    public function render()
    {
        return view('livewire.part-form', [
            'enumOptions' => [
                'status' => ['Operational', 'Needs Repair', 'Non-operational'],
                'condition' => ['New', 'Excellent', 'Good', 'Fair', 'Poor', 'Defective']
            ]
        ]);
    }
}
