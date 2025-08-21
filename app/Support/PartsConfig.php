<?php



namespace App\Support;

class PartsConfig
{
    public static function unitRelations(): array
    {
        return [
            'processor',
            'cpuCooler',
            'motherboard',
            'memories',
            'graphicsCards',
            'powerSupply',
            'computerCase',
            'm2Ssds',
            'sataSsds',
            'hardDiskDrives',
            'monitor',
            'keyboard',
            'mouse',
            'headset',
            'speaker',
            'webCamera',
        ];
    }

    /**
     * Get all parts attached to a given System Unit,
     * formatted with labels for dropdowns or reports.
     */
    public static function getPartsForUnit($unit): array
    {
        if (!$unit) {
            return [];
        }

        $parts = [];
        $labels = self::typeLabels();

        foreach (self::unitRelations() as $relation) {
            if (!method_exists($unit, $relation)) {
                continue;
            }

            $items = $unit->$relation;

            // Normalize to iterable
            if (is_null($items)) {
                continue;
            } elseif ($items instanceof \Illuminate\Database\Eloquent\Model) {
                $items = collect([$items]); // wrap single model
            } elseif (!($items instanceof \Illuminate\Support\Collection)) {
                $items = collect([]); // fallback safety
            }

            foreach ($items as $item) {
                $parts[] = [
                    'id' => $item->id ?? null,
                    'type' => $relation,
                    'label' => ($labels[$relation] ?? ucfirst($relation))
                        . ' - ' . ($item->model ?? $item->serial_number ?? $item->brand ?? 'Unknown'),
                ];
            }
        }

        return $parts;
    }


    /**
     * Build the parts config.
     * If $components/$peripherals are omitted, fall back to static type lists.
     */
    public static function get(array $components = null, array $peripherals = null): array
    {
        // If not provided, use all known types
        if ($components === null) {
            $components = array_fill_keys(self::componentTypes(), true);
        }
        if ($peripherals === null) {
            $peripherals = array_fill_keys(self::peripheralTypes(), true);
        }

        $labels = self::typeLabels();
        $allParts = array_merge(array_keys($components), array_keys($peripherals));

        $config = [];
        foreach ($allParts as $type) {
            $config[$type] = [
                'label' => $labels[$type] ?? ucfirst($type),
                'sub' => self::getSubtitle($type),
                // IMPORTANT: this must be the SystemUnit relation name
                'value' => $type,
                // Provide a closure for table-friendly formatting
                'formatter' => self::getFormatter($type),
            ];
        }

        return $config;
    }

    /**
     * Small helper for column subtitles (shown under header).
     */
    public static function getSubtitle(string $type): ?string
    {
        $map = [
            'processor' => '(model)',
            'motherboard' => '(model)',
            'memories' => '(type & capacity)',
            'graphicsCards' => '(model)',
            'm2Ssds' => '(type & capacity)',
            'sataSsds' => '(type & capacity)',
            'hardDiskDrives' => '(type & capacity)',
            'monitor' => '(model)',
            'keyboard' => '(model)',
            'mouse' => '(model)',
            'headset' => '(model)',
            'speaker' => '(model)',
            'webCamera' => '(model)',
            'powerSupply' => '(model)',
            'cpuCooler' => '(model)',
            'computerCase' => '(model)',
        ];
        return $map[$type] ?? null;
    }

    /**
     * Table-friendly formatter factory:
     * returns a Closure(array $part): array<string,string>
     */
    public static function getFormatter(string $type): \Closure
    {
        $defaultFields = self::defaultFields($type);

        return function ($part) use ($defaultFields): array {
            // Convert models/objects to array
            if (is_object($part)) {
                if (method_exists($part, 'toArray')) {
                    $part = $part->toArray();
                } else {
                    $part = (array) $part;
                }
            }

            // Ensure it's still an array
            if (!is_array($part)) {
                return [];
            }

            $out = [];

            // Prioritize key identifiers
            if (!empty($part['brand']))
                $out['Brand'] = $part['brand'];
            if (!empty($part['model']))
                $out['Model'] = $part['model'];
            if (!empty($part['status']))
                $out['Status'] = $part['status'];
            if (!empty($part['condition']))
                $out['Condition'] = $part['condition'];

            // Add remaining non-empty fields defined for this type
            $skip = ['brand', 'model', 'status', 'condition', 'date_purchased', 'system_unit_id', 'id', 'created_at', 'updated_at'];
            foreach ($defaultFields as $field => $_) {
                if (in_array($field, $skip, true))
                    continue;
                if (isset($part[$field]) && $part[$field] !== '' && $part[$field] !== null) {
                    $label = ucwords(str_replace('_', ' ', $field));
                    $out[$label] = (string) $part[$field];
                }
            }

            return $out;
        };
    }


    // … keep your existing componentTypes(), peripheralTypes(), typeLabels(), modelMap(),
    // defaultFields(), validationRules(), enumOptions(), getAvailableParts() as-is.



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
    //labels
    public static function typeLabels(): array
    {
        return [
            // Components
            'processor' => 'Processor',
            'cpuCooler' => 'CPU Cooler',
            'motherboard' => 'Motherboard',
            'memories' => 'Memory',
            'graphicsCards' => 'Graphics Card',
            'm2Ssds' => 'M.2 SSD',
            'sataSsds' => 'SATA SSD',
            'hardDiskDrives' => 'Hard Disk Drive',
            'powerSupply' => 'Power Supply',
            'computerCase' => 'Computer Case',

            // Peripherals
            'monitor' => 'Monitor',
            'keyboard' => 'Keyboard',
            'mouse' => 'Mouse',
            'headset' => 'Headset',
            'speaker' => 'Speaker',
            'webCamera' => 'Web Camera',
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
        $connectionTypeEnum = 'nullable|in:Wired,Wireless';


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
                'capacity' => null,
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
                'connection_type' => null,
            ],
            'mouse' => $common + [
                'connection_type' => null,
            ],
            'headset' => $common + [
                'connection_type' => null,
            ],
            'speaker' => $common + [
                'connection_type' => null,
            ],
            'webCamera' => $common + [
                'resolution' => '',
                'connection_type' => null,
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
        $connectionTypeEnum = 'nullable|in:Wired,Wireless';

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
                'fields.capacity' => 'nullable|integer',
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
                'fields.panel_type' => 'nullable|in:IPS,TN,VA,OLED', // 👈 enforce enum
            ],

            'keyboard' => $common + [
                'fields.connection_type' => $connectionTypeEnum,
            ],
            'mouse' => $common + [
                'fields.connection_type' => $connectionTypeEnum,
            ],
            'headset' => $common + [
                'fields.connection_type' => $connectionTypeEnum,
            ],
            'speaker' => $common + [
                'fields.connection_type' => $connectionTypeEnum,
            ],
            'webCamera' => $common + [
                'fields.resolution' => 'nullable|string|max:255',
                'fields.connection_type' => $connectionTypeEnum,
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
            'condition' => ['New', 'Excellent', 'Good', 'Fair', 'Poor', 'Defective'],
            'connection_type' => ['Wired', 'Wireless'],
            'panel_type' => ['IPS', 'TN', 'VA', 'OLED'] // 👈 added here
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
