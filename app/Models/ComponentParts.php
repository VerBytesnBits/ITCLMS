<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComponentParts extends Model
{
    protected $fillable = [
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
    ];

    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function maintenances()
    {
        return $this->morphMany(Maintenance::class, 'maintainable');
    }


}
