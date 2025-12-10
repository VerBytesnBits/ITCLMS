<?php

namespace App\Livewire\Reports;

use App\Models\ComponentParts;
use App\Models\SystemUnit;
use App\Models\Room;
use App\Models\Peripheral;
use Livewire\Component;
use Spatie\LaravelPdf\Facades\Pdf;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.app', ['title' => 'Units'])]
class UnitReport extends Component
{
    public $includeComponents = false;
    public $includePeripherals = false;
    public $includeHistory = false;
    public $pdfUrl = null;
    protected $previousPdf = null; // 🆕 track last generated PDF

    // Filters
    public $selectedRoom = null;
    public array $selectedComponentParts = [];
    public array $selectedPeripheralTypes = [];

    // Dropdown data
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
                'includeHistory',
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
        if (!($this->includeComponents || $this->includePeripherals || $this->includeHistory)) {
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
        // Delete previous PDF if exists
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
            $units->each(function ($unit) {
                $unit->components = $unit->components
                    ->whereIn('part', $this->selectedComponentParts)
                    ->values();
            });
        }

        if (!empty($this->selectedPeripheralTypes)) {
            $units->each(function ($unit) {
                $unit->peripherals = $unit->peripherals
                    ->whereIn('type', $this->selectedPeripheralTypes)
                    ->values();
            });
        }

        $pdfBuilder = Pdf::view('pdf.unit-report', [
            'units' => $units,
            'includeComponents' => $this->includeComponents,
            'includePeripherals' => $this->includePeripherals,
            'includeHistory' => $this->includeHistory,
            'selectedComponentParts' => $this->selectedComponentParts,
            'selectedPeripheralTypes' => $this->selectedPeripheralTypes,
            'selectedRoom' => $this->selectedRoom,
        ])
        ->footerHtml(view('pdf.partials.footer')->render())
        ->format('A4');

        // Unique file name
        $fileName = 'reports/unit_report_' . Str::uuid() . '.pdf';
        $pdfBuilder->disk('public')->save($fileName);

        // Store last generated PDF path for deletion next time
        $this->previousPdf = $fileName;

        return $fileName;
    }

    public function render()
    {
        return view('livewire.reports.unit-report');
    }
}
