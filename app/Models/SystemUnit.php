<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes; // <--- add this
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Jobs\GenerateAssetCode;

class SystemUnit extends Model
{
    use HasFactory, LogsActivity, SoftDeletes; // <--- include SoftDeletes

    protected $fillable = ['name', 'serial_number', 'status', 'room_id', 'qr_code_path'];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'serial_number', 'status', 'room_id'])
            ->logOnlyDirty()
            ->useLogName('system_unit')
            ->setDescriptionForEvent(fn(string $eventName) => match ($eventName) {
                'created' => 'added a new System Unit (' . $this->getSystemUnitLabel() . ')',
                'updated' => 'modified a System Unit (' . $this->getSystemUnitLabel() . ')',
                'deleted' => 'removed a System Unit (' . $this->getSystemUnitLabel() . ')',
                default => $eventName,
            });
    }

    protected function getSystemUnitLabel(): string
    {
        return trim(implode(' ', array_filter([
            $this->name ?? null,
            $this->serial_number ? "SN: {$this->serial_number}" : null,
            $this->status ? "({$this->status})" : null,
            $this->room?->name ? "in Room: {$this->room->name}" : null,
        ]))) ?: "ID: {$this->id}";
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
        parent::booted();

        // --- DELETE / RESTORE LOGIC ---
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




        static::created(function ($unit) {
            if ($unit->serial_number && empty($unit->qr_code_path)) {
                GenerateAssetCode::dispatch(self::class, $unit->id, 'qr');
            }
        });

        static::updated(function ($unit) {
            if ($unit->isDirty('serial_number')) {
                GenerateAssetCode::dispatch(self::class, $unit->id, 'qr');
            }
        });


    }

    protected static function generateAndSaveQr($serialNumber)
    {
        // Make sure the public disk is linked
        $fileName = 'qrcodes/' . Str::slug($serialNumber) . '-' . Str::random(6) . '.png';

        // Generate QR data (could be URL or plain text)
        $qrContent = url('/units/' . urlencode($serialNumber));
        // Or simply: $qrContent = $serialNumber;

        // Create QR code PNG
        $qrImage = QrCode::format('png')
            ->size(250)
            ->margin(2)
            ->generate($qrContent);

        // Save to storage/app/public/qrcodes/
        Storage::disk('public')->put($fileName, $qrImage);

        // Return the path accessible from browser
        return 'storage/' . $fileName;
    }

    public function checkOperationalStatus(): array
    {
        // If unit name does NOT contain "PC" → manual status, no missing
        if (stripos($this->name, 'pc') === false) {
            return [
                'status' => $this->status,
                'missing' => [
                    'components' => [],
                    'peripherals' => []
                ]
            ];
        }



        // Required for desktops / system units
        $requiredComponents = ['CPU', 'Motherboard', 'RAM', 'Casing', 'Storage'];
        $requiredPeripherals = ['Monitor', 'Keyboard', 'Mouse'];

        $missingComponents = [];
        $missingPeripherals = [];

        // Check components
        foreach ($requiredComponents as $type) {
            $component = $this->components->firstWhere('part', $type);
            if (!$component || $component->status !== 'In Use') {
                $missingComponents[] = $type;
            }
        }

        // Check peripherals
        foreach ($requiredPeripherals as $type) {
            $peripheral = $this->peripherals->firstWhere('type', $type);
            if (!$peripheral || $peripheral->status !== 'In Use') {
                $missingPeripherals[] = $type;
            }
        }

        $status = (empty($missingComponents) && empty($missingPeripherals))
            ? 'Operational'
            : 'Non-operational';

        // Auto-update DB only for desktops
        if ($this->status !== $status) {
            $this->update(['status' => $status]);
        }

        return [
            'status' => $status,
            'missing' => [
                'components' => $missingComponents,
                'peripherals' => $missingPeripherals
            ]
        ];
    }





}
