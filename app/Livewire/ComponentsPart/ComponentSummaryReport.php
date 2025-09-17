<?php

namespace App\Livewire\ComponentsPart;

use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ComponentParts;
use App\Traits\HasInventorySummary;

class ComponentSummaryReport extends Component
{
    use HasInventorySummary;

    public $pdfBase64 = null;
    public $showPreview = false;

    public function exportPDF()
    {
        // 🔄 Use trait instead of repeating SQL
        $summary = $this->getInventorySummary(
            ComponentParts::class,
            'part',
            ['brand', 'model', 'capacity' , 'type'] // <- adjust these columns to your schema
        );

        $pdf = Pdf::loadView('livewire.components-part.components-summary-pdf', [
            'summary' => $summary
        ])->setPaper('A4', 'portrait');

        $this->pdfBase64 = base64_encode($pdf->output());
        $this->showPreview = true;
    }

    public function downloadPDF()
    {
        $summary = $this->getInventorySummary(
            ComponentParts::class,
            'part',
            ['brand', 'model', 'capacity' , 'type'] // <- adjust as needed
        );

        return response()->streamDownload(function () use ($summary) {
            echo Pdf::loadView('livewire.components-part.components-summary-pdf', [
                'summary' => $summary
            ])->output();
        }, 'component-summary.pdf');
    }

    public function render()
    {
        return view('livewire.components-part.component-summary-report');
    }
}
