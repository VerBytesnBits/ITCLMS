<?php
namespace App\Support;

class StatusConfig
{
    public static function conditions(): array
    {
        return [
            'Excellent' => 'bg-green-100 text-green-700',
            'Good' => 'bg-blue-100 text-blue-700',
            'Fair' => 'bg-yellow-100 text-yellow-700',
            'Poor' => 'bg-red-100 text-red-700',
        ];
    }

    public static function statuses(): array
    {
        return [
            'Available' => 'bg-green-100 text-green-700',
            'Operational' => 'bg-green-100 text-green-700',
            'Under Maintenance' => 'bg-yellow-100 text-yellow-700',
            'Needs Repair' => 'bg-yellow-100 text-yellow-700',
            'Non-operational' => 'bg-red-100 text-red-700',
            'Defective' => 'bg-red-100 text-red-700',
        ];
    }
}
