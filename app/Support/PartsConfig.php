<?php

namespace App\Support;

class PartsConfig
{
    /**
     * Components
     */
    public static function componentTypes(): array
    {
        return [
            'processor',
            'cpuCooler',
            'motherboard',
            'memories',       // still plural
            'graphicsCards',  // still plural
            'powerSupply',
            'computerCase',
            'm2Ssds',         // still plural
            'sataSsds',       // still plural
            'hardDiskDrives'  // still plural
        ];
    }

    /**
     * Peripherals
     */
    public static function peripheralTypes(): array
    {
        return [
            'monitor',
            'keyboard',
            'mouse',
            'headset',
            'speaker',
            'webCamera'
        ];
    }

    /**
     * Map types to model classes
     */
    public static function modelMap(): array
    {
        return [
            'processor' => \App\Models\Processor::class,
            'motherboard' => \App\Models\Motherboard::class,
            'memories' => \App\Models\Memory::class,
            'graphicsCards' => \App\Models\GraphicsCard::class,
            'powerSupply' => \App\Models\PowerSupply::class,
            'computerCase' => \App\Models\ComputerCase::class,
            'cpuCooler' => \App\Models\CpuCooler::class,
            'hardDiskDrives' => \App\Models\HardDiskDrive::class,
            'sataSsds' => \App\Models\SataSsd::class,
            'm2Ssds' => \App\Models\M2Ssd::class,
            'keyboard' => \App\Models\Keyboard::class,
            'mouse' => \App\Models\Mouse::class,
            'headset' => \App\Models\Headset::class,
            'monitor' => \App\Models\Display::class,
            'speaker' => \App\Models\Speaker::class,
            'webCamera' => \App\Models\WebDigitalCamera::class,
        ];
    }

    /**
     * Default fields for each type
     */
    public static function defaultFields(string $type): array
    {
        $common = [
            'brand' => '',
            'model' => '',
            'serial_number' => '',
            'status' => 'Operational',
            'condition' => 'New',
            'date_purchased' => null
        ];

        $map = [
            'processor' => $common + [
                'generation' => '',
                'cores' => null,
                'threads' => null,
                'base_clock' => null,
                'boost_clock' => null,
            ],
            'cpuCooler' => $common + [
                'type' => '',
                'fan_size' => null
            ],
            'motherboard' => $common + [
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
            'powerSupply' => $common + [
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
                'panel_type' => null
            ],
            'keyboard' => $common + [
                'connection_type' => ''
            ],
            'mouse' => $common + [
                'connection_type' => ''
            ],
            'headset' => $common + [
                'connection_type' => ''
            ],
            'speaker' => $common + [
                'connection_type' => ''
            ],
            'webCamera' => $common + [
                'resolution' => '',
                'connection_type' => ''
            ],
        ];

        return $map[$type] ?? $common;
    }

    /**
     * Validation rules for each type
     */
    public static function validationRules(string $type): array
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
            'processor' => $common + [
                'fields.generation' => 'nullable|string|max:255',
                'fields.cores' => 'nullable|integer|min:1',
                'fields.threads' => 'nullable|integer|min:1',
                'fields.base_clock' => 'nullable|numeric',
                'fields.boost_clock' => 'nullable|numeric',
            ],
            'cpuCooler' => $common + [
                'fields.type' => 'nullable|string|max:255',
                'fields.fan_size' => 'nullable|integer|min:1',
            ],
            'motherboard' => $common + [
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
            'powerSupply' => $common + [
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
            'keyboard' => $common + [
                'fields.connection_type' => 'nullable|string|max:255',
            ],
            'mouse' => $common + [
                'fields.connection_type' => 'nullable|string|max:255',
            ],
            'headset' => $common + [
                'fields.connection_type' => 'nullable|string|max:255',
            ],
            'speaker' => $common + [
                'fields.connection_type' => 'nullable|string|max:255',
            ],
            'webCamera' => $common + [
                'fields.resolution' => 'nullable|string|max:255',
                'fields.connection_type' => 'nullable|string|max:255',
            ],
        ];

        return $map[$type] ?? $common;
    }

    /**
     * Enum options for dropdowns
     */
    public static function enumOptions(): array
    {
        return [
            'status' => ['Operational', 'Needs Repair', 'Non-operational'],
            'condition' => ['New', 'Excellent', 'Good', 'Fair', 'Poor', 'Defective']
        ];
    }

    /**
     * Available parts query
     */
    public static function getAvailableParts(array $types, ?int $unitId = null, array $currentSelections = []): array
    {
        $available = [];
        $modelMap = self::modelMap();

        foreach ($types as $type) {
            if (!isset($modelMap[$type])) {
                continue;
            }

            $model = $modelMap[$type];
            $query = $model::whereNull('system_unit_id');

            if ($unitId) {
                $query->orWhere('system_unit_id', $unitId);
            }

            $items = $query->get()->toArray();

            if (!empty($currentSelections[$type])) {
                $selectedIds = array_column($currentSelections[$type], 'id');
                $items = array_filter($items, fn($item) => !in_array($item['id'], $selectedIds));
            }

            $available[$type] = array_values($items);
        }

        return $available;
    }
}
