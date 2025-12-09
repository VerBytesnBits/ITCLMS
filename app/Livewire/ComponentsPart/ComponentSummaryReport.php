<?php

namespace App\Livewire\ComponentsPart;

use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ComponentParts;
use App\Traits\HasInventorySummary;
use App\Models\Room;

class ComponentSummaryReport extends Component
{
    use HasInventorySummary;

    public $roomId;
    public $age;
    public $tab;

    public $pdfBase64 = null;
    public $showPreview = false;

    public function mount($roomId = null, $age = '', $tab = null)
    {
        $this->roomId = $roomId;
        $this->age = $age;
        $this->tab = $tab;
    }

    protected function getFilters(): array
    {
        $filters = ['__age' => $this->age];

        if ($this->roomId) {
            $filters['room_id'] = $this->roomId;
        }

        if ($this->tab) {
            $filters['part'] = $this->tab;
        }

        return $filters;
    }

    protected function prepareSummary($rawSummary)
    {
        return collect($rawSummary)
            ->map(function ($items, $part) {
                return collect($items)->map(function ($i) {
                    return [
                        'description' => $i['description'] ?? 'N/A', // Use SQL description directly
                        'total' => (int) ($i['total'] ?? 0),
                        'available' => (int) ($i['available'] ?? 0),
                        'in_use' => (int) ($i['in_use'] ?? 0),
                        'defective' => (int) ($i['defective'] ?? 0),
                    ];
                })->values();
            });
    }

    public function exportPDF()
    {
        $filters = $this->getFilters();

        $raw = $this->getInventorySummary(
            ComponentParts::class,
            'part',
            ['brand', 'model', 'capacity', 'type'], // description is built in SQL
            '',
            '',
            $filters
        );

        $summary = $this->prepareSummary($raw);

        $roomName = $this->roomId
            ? Room::find($this->roomId)?->name ?? 'Unknown Room'
            : 'All Rooms';

        $pdf = Pdf::loadView('livewire.components-part.components-summary-pdf', [
            'summary' => $summary,
            'roomName' => $roomName,
        ])->setPaper('A4', 'portrait');

        $this->pdfBase64 = base64_encode($pdf->output());
        $this->showPreview = true;
    }

    public function downloadPDF()
    {
        $filters = $this->getFilters();

        $raw = $this->getInventorySummary(
            ComponentParts::class,
            'part',
            ['brand', 'model', 'capacity', 'type'],
            '',
            '',
            $filters
        );

        $summary = $this->prepareSummary($raw);

        $roomName = $this->roomId
            ? Room::find($this->roomId)?->name ?? 'Unknown Room'
            : 'All Rooms';

        return response()->streamDownload(function () use ($summary, $roomName) {
            echo Pdf::loadView('livewire.components-part.components-summary-pdf', [
                'summary' => $summary,
                'roomName' => $roomName,
            ])->output();
        }, 'component-summary.pdf');
    }

    public function render()
    {
        return view('livewire.components-part.component-summary-report');
    }
}
