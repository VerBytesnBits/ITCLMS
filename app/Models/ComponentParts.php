<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
class ComponentParts extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'system_unit_id',
        'serial_number',
        'brand',
        'model',
        'type',
        'capacity',
        'speed',
        'part',
        'condition',
        'status',
        'purchase_date',
        'warranty_expires_at',
        'warranty_period_months',
        'retirement_action',
        'retirement_notes',
        'retired_at',
    ];
    protected $casts = [
        'purchase_date' => 'date',
        'warranty_expires_at' => 'date',
        'retired_at' => 'datetime',
        'warranty_period_months' => 'integer', 
    ];


    protected static function booted()
    {
        static::saving(function ($part) {
            if ($part->purchase_date && $part->warranty_period_months) {
                $part->warranty_expires_at = $part->purchase_date->copy()
                    ->addMonths($part->warranty_period_months);
            }

        });
    }


    public function getWarrantyExpiresAtAttribute()
    {
        if (!$this->purchase_date || !$this->warranty_period_months) {
            return null;
        }

        return Carbon::parse($this->purchase_date)->addMonths($this->warranty_period_months);
    }


    // ✅ Warranty status
    public function getWarrantyStatusAttribute(): string
    {
        if (!$this->warranty_expires_at) {
            return 'No Warranty';
        }

        if (now()->gt($this->warranty_expires_at)) {
            return 'Expired';
        }

        if (now()->diffInDays($this->warranty_expires_at) <= 30) {
            return 'Expiring Soon';
        }

        return 'Valid';
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['serial_number','status', 'retirement_action', 'retirement_notes', 'condition', 'system_unit_id'])
            ->useLogName('component');
    }

    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class)->withDefault([
            'name' => 'Unassigned',
        ]);
    }

    public function room()
    {
        return $this->belongsTo(Room::class)->withDefault([
            'name' => 'Unassigned',
        ]);
    }


    public function maintenances()
    {
        return $this->morphMany(Maintenance::class, 'maintainable');
    }
}
