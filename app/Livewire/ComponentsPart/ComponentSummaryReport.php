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

    public function exportPDF()
    {
        $filters = [
            'room_id' => $this->roomId,
            '__age' => $this->age,
        ];

        if ($this->tab) {
            $filters['part'] = $this->tab;
        }

        $summary = $this->getInventorySummary(
            ComponentParts::class,
            'part',
            ['brand', 'model', 'capacity', 'type'],
            '',
            '',
            $filters
        );



        // Get room name if a room is selected
        $roomName = $this->roomId ? Room::find($this->roomId)?->name : 'All Rooms';

        $pdf = Pdf::loadView('livewire.components-part.components-summary-pdf', [
            'summary' => $summary,
            'roomName' => $roomName, // pass the room name
        ])->setPaper('A4', 'portrait');


        $this->pdfBase64 = base64_encode($pdf->output());
        $this->showPreview = true;
    }

    public function downloadPDF()
    {
        $filters = [
            'room_id' => $this->roomId,
            '__age' => $this->age,
        ];

        if ($this->tab) {
            $filters['part'] = $this->tab;
        }

        $summary = $this->getInventorySummary(
            ComponentParts::class,
            'part',
            ['brand', 'model', 'capacity', 'type'],
            '',
            '',
            $filters
        );

        // Resolve room name
        $roomName = $this->roomId ? Room::find($this->roomId)?->name : 'All Rooms';

        return response()->streamDownload(function () use ($summary, $roomName) {
            echo Pdf::loadView('livewire.components-part.components-summary-pdf', [
                'summary' => $summary,
                'roomName' => $roomName, // Pass the room name to the PDF
            ])->output();
        }, 'component-summary.pdf');
    }

    public function render()
    {
        return view('livewire.components-part.component-summary-report');
    }
}
