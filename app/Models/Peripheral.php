<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Peripheral extends Model
{
    use HasFactory, LogsActivity;

     protected $fillable = [
        'system_unit_id',
        'room_id',
        'name',
        'serial_number',
        'brand',
        'model',
        'color',
        'type',
        'condition',
        'status',
        'warranty',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'serial_number', 'status', 'condition', 'system_unit_id'])
           
            ->useLogName('peripheral');
    }

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




