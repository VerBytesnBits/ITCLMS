<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes; // <--- add this
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SystemUnit extends Model
{
    use HasFactory, LogsActivity, SoftDeletes; // <--- include SoftDeletes

    protected $fillable = ['name', 'serial_number', 'status', 'room_id'];

    // Optional: log deleted events too
    protected static $logAttributes = ['name', 'serial_number', 'status', 'room_id'];

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

     protected static function booted()
    {
        static::deleting(function ($unit) {
            if ($unit->isForceDeleting()) {
                $unit->peripherals()->forceDelete();
                $unit->components()->forceDelete();
            } else {
                $unit->peripherals()->delete();
                $unit->components()->delete();
            }
        });

        static::restoring(function ($unit) {
            $unit->peripherals()->withTrashed()->restore();
            $unit->components()->withTrashed()->restore();
        });
    }
}
