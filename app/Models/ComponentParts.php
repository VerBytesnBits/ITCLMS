<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Carbon\Carbon;
use Milon\Barcode\DNS1D;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Jobs\GenerateAssetCode;

class ComponentParts extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'system_unit_id',
        'current_unit_id',
        'room_id',
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
        'barcode_path',
    ];


    protected $casts = [
        'purchase_date' => 'date',
        'warranty_expires_at' => 'date',
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
            ->logOnly([
                'serial_number',
                'status',
                'system_unit_id',
                'current_unit_id',
            ])
            ->logOnlyDirty()
            ->useLogName('component')
            ->setDescriptionForEvent(fn(string $eventName) => match ($eventName) {
                'created' => 'added a new Component (' . $this->getComponentLabel() . ')',
                'updated' => 'modified a Component (' . $this->getComponentLabel() . ')',
                'deleted' => 'removed a Component (' . $this->getComponentLabel() . ')',
                default => $eventName,
            });
    }
    protected function getComponentLabel(): string
    {
        return trim(implode(' ', array_filter([
            $this->brand ?? null,
            $this->model ?? null,
            $this->serial_number ? "SN: {$this->serial_number}" : null,
        ]))) ?: "ID: {$this->id}";
    }


    public function getWarrantyExpiresAtAttribute($value)
    {
        if ($value)
            return Carbon::parse($value);

        if (!$this->purchase_date || !$this->warranty_period_months) {
            return null;
        }

        return Carbon::parse($this->purchase_date)->addMonths($this->warranty_period_months);
    }

    public function getWarrantyStatusAttribute(): string
    {
        if (!$this->warranty_expires_at)
            return 'No Warranty';
        if (now()->gt($this->warranty_expires_at))
            return 'Expired';
        if (now()->diffInDays($this->warranty_expires_at) <= 30)
            return 'Expiring Soon';
        return 'Valid';
    }

    public function getWarrantyRemainingDaysAttribute(): ?int
    {
        return $this->warranty_expires_at
            ? now()->diffInDays($this->warranty_expires_at, false)
            : null;
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
            if ($part->isDirty(['purchase_date', 'warranty_period_months'])) {
                if ($part->purchase_date && $part->warranty_period_months) {
                    $purchaseDate = Carbon::parse($part->purchase_date);
                    $part->warranty_expires_at = $purchaseDate->copy()->addMonths($part->warranty_period_months);
                } else {
                    $part->warranty_expires_at = null;
                }
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



        static::created(function ($part) {
            if ($part->serial_number && empty($part->barcode_path)) {
                GenerateAssetCode::dispatch(self::class, $part->id, 'barcode');
            }
        });

        static::updated(function ($part) {
            if ($part->isDirty('serial_number')) {
                GenerateAssetCode::dispatch(self::class, $part->id, 'barcode');
            }
        });

    }



    protected static function generateAndSaveBarcode($serialNumber)
    {
        $barcode = new DNS1D();

        // Create the barcode PNG in memory
        $barcodeData = $barcode->getBarcodePNG($serialNumber, 'C128', 2, 60, [0, 0, 0], true);

        // Decode base64 image data
        $image = base64_decode($barcodeData);

        // Create a unique file name
        $fileName = 'barcodes/' . Str::slug($serialNumber) . '-' . Str::random(6) . '.png';

        // Save to storage (public disk)
        Storage::disk('public')->put($fileName, $image);

        // Return the relative path (for example: storage/barcodes/...)
        return 'storage/' . $fileName;
    }




}
