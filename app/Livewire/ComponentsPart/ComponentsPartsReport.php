<?php

namespace App\Livewire\ComponentsPart;

use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ComponentParts;
use App\Models\Room;

class ComponentsPartsReport extends Component
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

    protected function getGroupedComponents()
    {
        $filters = $this->getFilters();

        //  FIX 1: Eager load the required nested relationships (systemUnit.room) AND the direct room relationship
        $query = ComponentParts::with(['systemUnit.room', 'room']);

        //  FIX 2: Apply filter using a WHERE OR WHEREHAS clause to cover both direct and nested room assignments
        if (isset($filters['room_id'])) {
            $query->where(function ($q) use ($filters) {
                // Check for direct room assignment
                $q->where('room_id', $filters['room_id'])
                  // OR check for room assignment via SystemUnit
                  ->orWhereHas('systemUnit', fn($q2) =>
                      $q2->where('room_id', $filters['room_id'])
                  );
            });
        }

        $components = $query->get();

        return $components
            //  Grouping logic must now prioritize SystemUnit's room, but fall back to ComponentPart's direct room, or 'Unknown Room'
            ->groupBy(fn($item) => $item->systemUnit->room->name ?? $item->room->name ?? 'Unknown Room')
            ->map(function ($items) {
                return $items
                    ->groupBy(fn($i) => $i->brand . '|' . $i->model . '|' . $i->capacity . '|' . $i->type)
                    ->map(fn($group) => [
                        'description' => trim($group[0]->brand . ' ' . $group[0]->model . ' ' . $group[0]->capacity . ' ' . $group[0]->type . ' ' . $group[0]->speed),
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
        $grouped = $this->getGroupedComponents();

        $roomName = $this->roomId
            ? Room::find($this->roomId)?->name ?? 'Unknown Room'
            : 'All Rooms';

        $pdf = Pdf::loadView('livewire.components-part.components-summary-pdf', [
            'grouped' => $grouped,
            'roomName' => $roomName,
        ])->setPaper('letter', 'portrait');

        $this->pdfBase64 = base64_encode($pdf->output());
        $this->showPreview = true;
    }

    public function downloadPDF()
    {
        $grouped = $this->getGroupedComponents();

        $roomName = $this->roomId
            ? Room::find($this->roomId)?->name ?? 'Unknown Room'
            : 'All Rooms';

        return response()->streamDownload(function () use ($grouped, $roomName) {
            echo Pdf::loadView('livewire.components-part.components-summary-pdf', [
                'grouped' => $grouped,
                'roomName' => $roomName,
            ])->output();
        }, 'components-summary.pdf');
    }

    protected function getBarcodesForPreview()
    {
        // Eager load both relationships
        $query = ComponentParts::with(['systemUnit.room', 'room']);

        if ($this->roomId) {
            // Apply the same dual-filter logic here
            $query->where(function ($q) {
                // Check for direct room assignment
                $q->where('room_id', $this->roomId)
                  // OR check for room assignment via SystemUnit
                  ->orWhereHas(
                    'systemUnit',
                    fn($q2) => $q2->where('room_id', $this->roomId)
                );
            });
        }

        $items = $query
           
            ->select('id', 'brand', 'model', 'type', 'speed' , 'barcode_path', 'room_id', 'system_unit_id')
            ->get();

        return $items->map(function ($item) {
            
        
            $roomName = $item->systemUnit->room->name ?? $item->room->name ?? 'Unknown Room';

            if (empty($item->barcode_path)) {
                return [
                    'description' => trim($item->brand . ' ' . $item->model . ' ' . $item->type . ' ' . $item->speed),
                    'room' => $roomName,
                    'barcode' => null,
                ];
            }

            $fullPath = public_path($item->barcode_path);

            $barcodeBase64 = file_exists($fullPath)
                ? base64_encode(file_get_contents($fullPath))
                : null;

            return [
                'description' => trim($item->brand . ' ' . $item->model . ' ' . $item->type . ' ' . $item->speed),
                'room' => $roomName,
                'barcode' => $barcodeBase64,
            ];
        });
    }

    public function previewBarcodes()
    {
        $items = $this->getBarcodesForPreview();

        $pdf = Pdf::loadView('livewire.components-part.components-barcode-preview', [
            'items' => $items,
        ])->setPaper('letter', 'portrait');

        $this->pdfBase64 = base64_encode($pdf->output());
        $this->showPreview = true;
    }

    public function render()
    {
        return view('livewire.components-part.components-parts-report', [
            'grouped' => $this->getGroupedComponents(),
            'rooms' => Room::orderBy('name')->get(),
        ]);
    }
}