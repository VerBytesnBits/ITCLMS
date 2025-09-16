<?php

namespace App\Livewire;

use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\SystemUnit;
use App\Models\ComponentParts;
use App\Models\Peripheral;
use App\Models\QrGeneration;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app', ['title' => 'Qr'])]
class QrManager extends Component
{
    public string $type = 'unit';
    public ?int $itemId = null;
    public array $records = [];
    public ?string $qr = null;

    public function mount()
    {
        $this->loadItems();
    }

    public function updatedType()
    {
        $this->itemId = null;
        $this->qr = null;
        $this->loadItems();
    }

    protected function loadItems(): void
    {
        $this->records = match ($this->type) {
            'unit' => SystemUnit::pluck('serial_number', 'id')->toArray(),
            'component' => ComponentParts::pluck('serial_number', 'id')->toArray(),
            'peripheral' => Peripheral::pluck('serial_number', 'id')->toArray(),
            default => [],
        };
    }

    public function generateQr(): void
    {
        if (!$this->itemId || !in_array($this->type, ['unit', 'component', 'peripheral'])) {
            $this->addError('itemId', 'Please select a valid type and item.');
            return;
        }

        // Determine model class
        $class = match ($this->type) {
            'unit' => SystemUnit::class,
            'component' => ComponentParts::class,
            'peripheral' => Peripheral::class,
        };

        $item = $class::findOrFail($this->itemId);

        // Use getMorphClass() for consistent polymorphic type
        $itemType = $item->getMorphClass();

        // Create permanent QR record safely
        $qrRecord = QrGeneration::firstOrCreate([
            'item_id' => $item->id,
            'item_type' => $itemType,
        ]);

        // Generate QR image for preview
        $this->qr = base64_encode(
            QrCode::format('png')->size(200)->generate(
                route('tracking.show', [
                    'type' => strtolower(class_basename($itemType)),
                    'serial' => $item->serial_number
                ])
            )
        );
    }

    public function render()
    {
        $activeQrs = QrGeneration::with('item')
            ->orderByDesc('created_at')
            ->get();

        // Generate QR base64 for each record (on the fly)
        $activeQrsWithQr = $activeQrs->map(function ($qrRecord) {
            $item = $qrRecord->item;

            if (!$item) {
                return [
                    'record' => $qrRecord,
                    'qr' => null,
                ];
            }

            $qrBase64 = base64_encode(
                QrCode::format('png')->size(100)->generate(
                    route('tracking.show', [
                        'type' => strtolower(class_basename($item->getMorphClass())),
                        'serial' => $item->serial_number,
                    ])
                )
            );

            return [
                'record' => $qrRecord,
                'qr' => $qrBase64,
            ];
        })->toArray();

        return view('livewire.qr-manager', [
            'activeQrsWithQr' => $activeQrsWithQr,
        ]);
    }

}
