<?php

namespace App\Livewire\Peripherals;

use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Peripheral;
use App\Models\Room;

class PeripheralsReport extends Component
{
    public $roomId = null;
    public $pdfBase64 = null;
    public $showPreview = false;

    public function mount($roomId = null)
    {
        $this->roomId = $roomId;
    }

    protected function getFilters(): array
    {
        $filters = [];

        if ($this->roomId) {
            $filters['room_id'] = $this->roomId;
        }

        return $filters;
    }

    protected function getGroupedPeripherals()
    {
        $filters = $this->getFilters();

        $query = Peripheral::with('room');

        if (isset($filters['room_id'])) {
            $query->where('room_id', $filters['room_id']);
        }

        $peripherals = $query->get();

        // Group by room, then by unique item (brand + model + type)
        return $peripherals
            ->groupBy(fn($item) => $item->room?->name ?? 'Unknown Room')
            ->map(function ($items) {
                return $items
                    ->groupBy(fn($i) => $i->brand . '|' . $i->model . '|' . $i->type)
                    ->map(fn($group) => [
                        'description' => trim($group[0]->brand . ' ' . $group[0]->model . ' ' . $group[0]->type),
                        'total' => $group->count(),
                        'available' => $group->filter(fn($i) => in_array(strtolower($i->status), ['available', 'in stock']))->count(),
                        'in_use' => $group->filter(fn($i) => strtolower($i->status) === 'in use')->count(),
                        'defective' => $group->filter(fn($i) => strtolower($i->status) === 'defective')->count(),
                    ])
                    ->values();
            });
    }

    public function exportPDF()
    {
        $grouped = $this->getGroupedPeripherals();

        $roomName = $this->roomId
            ? Room::find($this->roomId)?->name ?? 'Unknown Room'
            : 'All Rooms';

        $pdf = Pdf::loadView('livewire.peripherals.peripherals-summary-pdf', [
            'grouped' => $grouped,
            'roomName' => $roomName,
        ])->setPaper('A4', 'portrait');

        $this->pdfBase64 = base64_encode($pdf->output());
        $this->showPreview = true;
    }

    public function downloadPDF()
    {
        $grouped = $this->getGroupedPeripherals();

        $roomName = $this->roomId
            ? Room::find($this->roomId)?->name ?? 'Unknown Room'
            : 'All Rooms';

        return response()->streamDownload(function () use ($grouped, $roomName) {
            echo Pdf::loadView('livewire.peripherals.peripherals-summary-pdf', [
                'grouped' => $grouped,
                'roomName' => $roomName,
            ])->output();
        }, 'peripherals-summary.pdf');
    }
    protected function getBarcodesForPreview()
    {
        $query = Peripheral::query();

        if ($this->roomId) {
            $query->where('room_id', $this->roomId);
        }

        $items = $query->select('id', 'brand', 'model', 'type', 'barcode_path', 'room_id')
            ->with('room')
            ->orderBy('room_id')
            ->get();

        return $items->map(function ($item) {

            if (empty($item->barcode_path)) {
                return [
                    'description' => trim($item->brand . ' ' . $item->model . ' ' . $item->type),
                    'room' => $item->room->name ?? 'Unknown Room',
                    'barcode' => null,
                ];
            }

            // ✔ FIXED — use public_path instead of storage_path
            $fullPath = public_path($item->barcode_path);

            $barcodeBase64 = file_exists($fullPath)
                ? base64_encode(file_get_contents($fullPath))
                : null;

            return [
                'description' => trim($item->brand . ' ' . $item->model . ' ' . $item->type),
                'room' => $item->room->name ?? 'Unknown Room',
                'barcode' => $barcodeBase64,
            ];
        });
    }

    public function previewBarcodes()
    {
        $items = $this->getBarcodesForPreview();

        $pdf = Pdf::loadView('livewire.peripherals.peripherals-barcode-preview', [
            'items' => $items,
        ])->setPaper('A4', 'portrait');

        $this->pdfBase64 = base64_encode($pdf->output());
        $this->showPreview = true;
    }

    public function render()
    {
        return view('livewire.peripherals.peripherals-report', [
            'grouped' => $this->getGroupedPeripherals(),
            'rooms' => Room::orderBy('name')->get(),
        ]);
    }
}
