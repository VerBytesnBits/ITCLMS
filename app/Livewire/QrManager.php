<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\SystemUnit;
use App\Models\ComponentParts;
use App\Models\Peripheral;
use App\Models\QrGeneration;
use Livewire\Attributes\Layout;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('components.layouts.app', ['title' => 'Qr Manager'])]
class QrManager extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public string $type = 'unit';
    public ?int $itemId = null;
    public array $records = [];

    // Single QR preview
    public ?string $qr = null;

    // Batch selection
    public array $itemIds = [];
    public array $qrBatch = [];

    // Lazy-loaded QR cache for paginated rows
    public array $activeQrsWithQr = [];

    public function mount(): void
    {
        $this->loadItems();
    }

    /** -------------------------
     * Item Loading
     * ------------------------- */
    protected function loadItems(): void
    {
        $this->records = match ($this->type) {
            'unit' => SystemUnit::pluck('serial_number', 'id')->toArray(),
            'component' => ComponentParts::pluck('serial_number', 'id')->toArray(),
            'peripheral' => Peripheral::pluck('serial_number', 'id')->toArray(),
            default => [],
        };
    }

    public function updatedType(): void
    {
        $this->resetPage();
        $this->itemId = null;
        $this->qr = null;
        $this->itemIds = [];
        $this->qrBatch = [];
        $this->loadItems();
    }

    public function selectAll(): void
    {
        $this->itemIds = array_keys($this->records);
    }

    /** -------------------------
     * Single QR
     * ------------------------- */
    public function generateQr(): void
    {
        if (!$this->itemId || !in_array($this->type, ['unit', 'component', 'peripheral'])) {
            $this->addError('itemId', 'Please select a valid type and item.');
            return;
        }

        $class = match ($this->type) {
            'unit' => SystemUnit::class,
            'component' => ComponentParts::class,
            'peripheral' => Peripheral::class,
        };

        $item = $class::findOrFail($this->itemId);
        $itemType = $item->getMorphClass();

        QrGeneration::firstOrCreate([
            'item_id' => $item->id,
            'item_type' => $itemType,
        ]);

        $this->qr = base64_encode(
            QrCode::format('png')->size(200)->generate(
                route('tracking.show', [
                    'type' => strtolower(class_basename($itemType)),
                    'serial' => $item->serial_number,
                ])
            )
        );
    }

    /** -------------------------
     * Batch QR
     * ------------------------- */
    public function generateQrBatch(): void
    {
        $this->qrBatch = [];

        if (empty($this->itemIds)) {
            $this->addError('itemIds', 'Please select at least one item.');
            return;
        }

        foreach ($this->itemIds as $id) {
            $serial = $this->records[$id] ?? null;
            if (!$serial)
                continue;

            $this->qrBatch[$id] = base64_encode(
                QrCode::format('png')->size(200)->generate(
                    route('tracking.show', [
                        'type' => $this->type,
                        'serial' => $serial,
                    ])
                )
            );

            QrGeneration::firstOrCreate([
                'item_id' => $id,
                'item_type' => match ($this->type) {
                    'unit' => SystemUnit::class,
                    'component' => ComponentParts::class,
                    'peripheral' => Peripheral::class,
                },
            ]);
        }
    }

    public function downloadQrBatchPdf()
    {
        if (empty($this->qrBatch)) {
            $this->addError('qrBatch', 'No QRs generated yet.');
            return;
        }

        $pdf = Pdf::loadView('pdf.qr-batch', [
            'qrs' => $this->qrBatch,
            'records' => $this->records,
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn() => print ($pdf->output()),
            'qr-codes.pdf'
        );
    }

    /** -------------------------
     * Lazy load QR for paginated records
     * ------------------------- */
    public function loadQr(int $qrId): void
    {
        $qrRecord = QrGeneration::with('item')->find($qrId);

        if (!$qrRecord || !$qrRecord->item) {
            $this->activeQrsWithQr[$qrId] = null;
            return;
        }

        $itemClass = class_basename($qrRecord->item->getMorphClass());

        // Map class names to friendly terms
        $typeMap = [
            'ComponentParts' => 'component',
            'SystemUnit' => 'unit',
            'Peripheral' => 'peripheral'
        ];

        $type = $typeMap[$itemClass] ?? strtolower($itemClass);

        $this->activeQrsWithQr[$qrId] = base64_encode(
            QrCode::format('png')->size(100)->generate(
                route('tracking.show', [
                    'type' => $type,
                    'serial' => $qrRecord->item->serial_number,
                ])
            )
        );
    }


    /** -------------------------
     * Render
     * ------------------------- */
    public function render()
    {
        $activeQrs = QrGeneration::with('item')
            ->orderByDesc('created_at')
            ->paginate(10); // 🔹 Pagination

        return view('livewire.qr-manager', [
            'activeQrs' => $activeQrs,
            'activeQrsWithQr' => $this->activeQrsWithQr,
        ]);
    }
}
