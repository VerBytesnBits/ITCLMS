<?php

namespace App\Support;

class PartsConfig
{
    public static function componentTypes(): array
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
            'hardDiskDrives'
        ];
    }

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

    public static function modelMap(): array
    {
        return [
            'processor' => \App\Models\Processor::class,
            'cpuCooler' => \App\Models\CpuCooler::class,
            'motherboard' => \App\Models\Motherboard::class,
            'memories' => \App\Models\Memory::class,
            'graphicsCards' => \App\Models\GraphicsCard::class,
            'powerSupply' => \App\Models\PowerSupply::class,
            'computerCase' => \App\Models\ComputerCase::class,
            'm2Ssds' => \App\Models\M2Ssd::class,
            'sataSsds' => \App\Models\SataSsd::class,
            'hardDiskDrives' => \App\Models\HardDiskDrive::class,
            'monitor' => \App\Models\Display::class,
            'keyboard' => \App\Models\Keyboard::class,
            'mouse' => \App\Models\Mouse::class,
            'headset' => \App\Models\Headset::class,
            'speaker' => \App\Models\Speaker::class,
            'webCamera' => \App\Models\WebDigitalCamera::class,
        ];
    }

    /**
     * Get available parts (those not assigned to another unit),
     * optionally including parts already assigned to the given unit ID.
     */
    public static function getAvailableParts(array $types, ?int $unitId = null, array $currentSelections = []): array
    {
        $available = [];
        $modelMap = self::modelMap();

        foreach ($types as $type) {
            $model = $modelMap[$type];

            // Base query: always include unassigned
            $query = $model::whereNull('system_unit_id');

            // In edit mode, also include parts assigned to this unit
            if ($unitId) {
                $query->orWhere('system_unit_id', $unitId);
            }

            $items = $query->get()->toArray();

            // Remove items already in current selections to avoid duplicates
            if (!empty($currentSelections[$type])) {
                $selectedIds = array_column($currentSelections[$type], 'id');
                $items = array_filter($items, fn($item) => !in_array($item['id'], $selectedIds));
            }

            $available[$type] = array_values($items);
        }

        return $available;
    }


}
