<?php

namespace App\Livewire\ComponentsPart;

use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ComponentParts;
use App\Traits\HasInventorySummary;

class ComponentSummaryReport extends Component
{
    protected $listeners = ['previewPrint' => 'exportPDF'];

    use HasInventorySummary;

    public $summary = [];

    public function mount()
    {
        // Generate summary using the trait
        $this->summary = $this->getInventorySummary(
            ComponentParts::class,
            'part',              // group by 'part'
            ['brand', 'model']   // description columns
        );
    }

    public function exportPDF()
    {
        $pdf = Pdf::loadView('livewire.components-part.component-summary-pdf', [
            'summary' => $this->summary
        ])->setPaper('A4', 'portrait');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'component-summary.pdf');
    }

    public function render()
    {
        return view('livewire.components-part.component-summary-report', [
            'summary' => $this->summary
        ]);
    }
}
