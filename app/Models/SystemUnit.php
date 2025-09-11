<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SystemUnit extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['name', 'serial_number', 'status', 'room_id'];

    // This is now required
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'serial_number', 'status', 'room_id'])
            ->logOnlyDirty()
            ->useLogName('system_unit');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function peripherals()
    {
        return $this->hasMany(Peripheral::class);
    }

    public function components()
    {
        return $this->hasMany(ComponentParts::class);
    }

    public function maintenances()
    {
        return $this->morphMany(Maintenance::class, 'maintainable');
    }
}
