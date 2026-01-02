<?php

namespace App\Livewire\Reports;

use App\Models\ComponentParts;
use App\Models\SystemUnit;
use App\Models\Room;
use App\Models\Peripheral;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


#[Layout('components.layouts.app', ['title' => 'Units'])]
class UnitReport extends Component
{
    public $includeComponents = false;
    public $includePeripherals = false;
    public $pdfUrl = null;
    protected $previousPdf = null;

    public $selectedRoom = null;
    public array $selectedComponentParts = [];
    public array $selectedPeripheralTypes = [];

    public $rooms = [];
    public $components = [];
    public $peripherals = [];

    public function mount()
    {
        $this->rooms = Room::orderBy('name')->get();
        $this->components = ComponentParts::select('part')->distinct()->orderBy('part')->pluck('part');
        $this->peripherals = Peripheral::select('type')->distinct()->orderBy('type')->pluck('type');
    }

    public function updated($propertyName)
    {
        if (
            in_array($propertyName, [
                'includeComponents',
                'includePeripherals',
                'selectedRoom',
                'selectedComponentParts',
                'selectedPeripheralTypes'
            ])
        ) {
            $this->pdfUrl = null;
        }
    }

    public function previewReport()
    {
        if (!($this->includeComponents || $this->includePeripherals)) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'No Selection',
                'text' => 'Please select at least one report option before generating.'
            ]);
            return;
        }

        $filePath = $this->generatePdf();
        $this->pdfUrl = asset("storage/{$filePath}");
    }

    protected function generatePdf(): string
    {
        // Delete previous PDF
        if ($this->previousPdf && Storage::disk('public')->exists($this->previousPdf)) {
            Storage::disk('public')->delete($this->previousPdf);
        }

        $query = SystemUnit::with(['room', 'components', 'peripherals']);

        if ($this->selectedRoom) {
            $query->where('room_id', $this->selectedRoom);
        }

        $units = $query->get();

        // Filter components & peripherals
        if (!empty($this->selectedComponentParts)) {
            $units->each(fn($unit) => $unit->components = $unit->components->whereIn('part', $this->selectedComponentParts)->values());
        }
        if (!empty($this->selectedPeripheralTypes)) {
            $units->each(fn($unit) => $unit->peripherals = $unit->peripherals->whereIn('type', $this->selectedPeripheralTypes)->values());
        }



        $pdf = Pdf::loadView('pdf.unit-report', [
            'units' => $units,
            'includeComponents' => $this->includeComponents,
            'includePeripherals' => $this->includePeripherals,
            'selectedRoom' => $this->selectedRoom,


            'conductedByName' => Auth::user()->name,
            'conductedByRole' => Auth::user()->getRoleNames()->first(),
            'labInCharge' => User::role('lab_incharge')->first(),
            'chairman' => User::role('chairman')->first(),

            'reportDate' => now()->format('F d, Y'),
        ])->setPaper('letter', 'landscape')
            ->setOption('isPhpEnabled', true); // MUST be true

        $canvas = $pdf->getDomPDF()->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $font = $fontMetrics->get_font("Helvetica", "normal");
            $x = $canvas->get_width() - 50; // right margin
            $y = $canvas->get_height() - 30; // bottom margin
            $canvas->text($x, $y, "System unit inventory $pageNumber of $pageCount", $font, 10, [0, 0, 0]);
        });


        // Save to storage
        $fileName = 'reports/unit_report_' . Str::uuid() . '.pdf';
        Storage::disk('public')->put($fileName, $pdf->output());

        $this->previousPdf = $fileName;

        return $fileName;
    }

    public function render()
    {
        return view('livewire.reports.unit-report');
    }
}
