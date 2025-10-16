<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Carbon\Carbon;

class ComponentParts extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'system_unit_id',
        'current_unit_id', // Track current active unit
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

    /** -------------------- Relationships -------------------- **/

    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class, 'system_unit_id')->withDefault([
            'name' => 'Unassigned',
        ]);
    }

    public function currentUnit()
    {
        return $this->belongsTo(SystemUnit::class, 'current_unit_id')->withDefault([
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

    /** -------------------- Activity Logging -------------------- **/

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['serial_number', 'status', 'retirement_action', 'retirement_notes', 'condition', 'system_unit_id', 'current_unit_id'])
            ->logOnlyDirty()
            ->useLogName('component');
    }

    /** -------------------- Warranty Helpers -------------------- **/

    public function getWarrantyExpiresAtAttribute()
    {
        if (!$this->purchase_date || !$this->warranty_period_months) {
            return null;
        }

        return Carbon::parse($this->purchase_date)->addMonths($this->warranty_period_months);
    }

    public function getWarrantyStatusAttribute(): string
    {
        if (!$this->warranty_expires_at) return 'No Warranty';
        if (now()->gt($this->warranty_expires_at)) return 'Expired';
        if (now()->diffInDays($this->warranty_expires_at) <= 30) return 'Expiring Soon';
        return 'Valid';
    }

    /** -------------------- Status / Decommission Helpers -------------------- **/

    public function isDecommissioned(): bool
    {
        return $this->deleted_at !== null || $this->status === 'Decommissioned';
    }

    public function markDecommissioned(string $action = null, string $notes = null)
    {
        $this->status = 'Decommissioned';
        $this->retirement_action = $action;
        $this->retirement_notes = $notes;
        $this->retired_at = now();
        $this->save();
        $this->delete(); // soft delete
    }

    /** -------------------- Restore / Reassign Helpers -------------------- **/

    public function restoreToUnit(?int $newUnitId = null)
    {
        $oldUnit = $this->current_unit_id;

        if ($newUnitId) {
            $this->current_unit_id = $newUnitId;
        }

        $this->restore();

        activity()
            ->performedOn($this)
            ->causedBy(auth()->user())
            ->withProperties([
                'from_unit_id' => $oldUnit,
                'to_unit_id' => $this->current_unit_id,
            ])
            ->log('Component restored and reassigned');
    }

    /** -------------------- Booted Events -------------------- **/

    protected static function booted()
    {
        // Warranty calculation
        static::saving(function ($part) {
            if ($part->purchase_date && $part->warranty_period_months) {
                $part->warranty_expires_at = $part->purchase_date->copy()
                    ->addMonths($part->warranty_period_months);
            }
        });

        // Log reassignment
        static::updating(function ($part) {
            if ($part->isDirty('current_unit_id')) {
                activity()
                    ->performedOn($part)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'from_unit_id' => $part->getOriginal('current_unit_id'),
                        'to_unit_id' => $part->current_unit_id,
                    ])
                    ->log('Component reassigned');
            }
        });
    }
}
