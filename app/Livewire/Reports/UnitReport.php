<?php

namespace App\Livewire\Reports;

use App\Models\ComponentParts;
use App\Models\SystemUnit;
use App\Models\Room;
use App\Models\Peripheral;
use Livewire\Component;
use Spatie\LaravelPdf\Facades\Pdf;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app', ['title' => 'Units'])]
class UnitReport extends Component
{
    public $includeComponents = false;
    public $includePeripherals = false;
    public $includeHistory = false;
    public $pdfUrl = null;

    // 🆕 Filters
    public $selectedRoom = null;
    public array $selectedComponentParts = []; // e.g. ['CPU', 'Motherboard']
    public array $selectedPeripheralTypes = []; // e.g. ['Monitor', 'Mouse']

    // 🆕 Dropdown data
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
        $query = SystemUnit::with(['room', 'components', 'peripherals']);

        if ($this->selectedRoom) {
            $query->where('room_id', $this->selectedRoom);
        }

        $units = $query->get();

        // Filter inside each system unit’s relations
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

        $fileName = 'reports/unit_report.pdf';
        $pdfBuilder->disk('public')->save($fileName);

        return $fileName;
    }
    

    public function render()
    {
        return view('livewire.reports.unit-report');
    }
}
