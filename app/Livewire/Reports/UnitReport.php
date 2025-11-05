<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Spatie\LaravelPdf\Facades\Pdf;
use App\Models\SystemUnit;

class UnitReport extends Component
{
    public $includeComponents = false;
    public $includePeripherals = false;
    public $includeHistory = false;
    public $pdfUrl = null;

    public function updated($propertyName)
    {
        // Whenever a checkbox changes, clear the preview
        if (in_array($propertyName, ['includeComponents', 'includePeripherals', 'includeHistory'])) {
            $this->pdfUrl = null;
        }
    }

    public function previewReport()
    {
        // ✅ Require at least one checkbox before generating
        if (!($this->includeComponents || $this->includePeripherals || $this->includeHistory)) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'No Selection',
                'text' => '⚠️ Please select at least one report option before generating.'
            ]);
            return;
        }

        $filePath = $this->generatePdf();
        $this->pdfUrl = asset("storage/{$filePath}");
    }



    public function render()
    {
        return view('livewire.reports.unit-report');
    }

    protected function generatePdf(): string
    {
        $units = SystemUnit::with(['components', 'peripherals'])->get();

        $pdfBuilder = Pdf::view('pdf.unit-report', [
            'units' => $units,
            'includeComponents' => $this->includeComponents,
            'includePeripherals' => $this->includePeripherals,
            'includeHistory' => $this->includeHistory,
        ])
            ->footerHtml(view('pdf.partials.footer')->render())
            ->format('A4');

        $fileName = 'reports/unit_report.pdf';
        $pdfBuilder->disk('public')->save($fileName);

        return $fileName;
    }
}
