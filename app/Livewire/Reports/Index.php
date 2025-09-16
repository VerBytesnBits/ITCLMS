<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SystemUnit;
use App\Models\ComponentParts;
use App\Models\Peripheral;
use App\Models\QrGeneration;
use Spatie\Activitylog\Models\Activity;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app', ['title' => 'Reports'])]
class Index extends Component
{
    use WithPagination;

    public $perPage = 20;
    public $search = '';
    public $reportType = 'inventory'; // inventory, qr, activity
    public $filterModel = ''; // optional filter by model type
    public $status = '';
    public $room = '';
    public $dateFrom = '';
    public $dateTo = '';

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingFilterModel()
    {
        $this->resetPage();
    }
    public function updatingReportType()
    {
        $this->resetPage();
    }

    public function render()
    {
        switch ($this->reportType) {
            case 'inventory':
                $records = $this->getInventoryRecords();
                break;
            case 'qr':
                $records = $this->getQrRecords();
                break;
            case 'activity':
                $records = $this->getActivityLogs();
                break;
            default:
                $records = collect();
        }

        return view('livewire.reports.index', [
            'records' => $records,
        ]);
    }


    public function exportQrPdf()
    {
        $qrs = $this->getQrRecords();

        $pdf = Pdf::loadView('reports.qr-pdf', [
            'qrs' => $qrs
        ])->setPaper('a4', 'portrait');

        // Return PDF inline for browser preview
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="qr-codes-report.pdf"',
        ]);
    }


    private function getInventoryRecords()
    {
        $query = collect();

        // System Units
        if ($this->filterModel === '' || $this->filterModel === 'SystemUnit') {
            $query = $query->merge(SystemUnit::with('room')->get());
        }

        // Component Parts
        if ($this->filterModel === '' || $this->filterModel === 'ComponentParts') {
            $components = ComponentParts::with('systemUnit.room')->get();
            $query = $query->merge($components);
        }

        // Peripherals
        if ($this->filterModel === '' || $this->filterModel === 'Peripheral') {
            $peripherals = Peripheral::with('systemUnit.room')->get();
            $query = $query->merge($peripherals);
        }

        // Filter by status
        if ($this->status) {
            $query = $query->filter(fn($item) => ($item->status ?? '') === $this->status);
        }

        // Filter by room
        if ($this->room) {
            $query = $query->filter(function ($item) {
                // SystemUnit has room directly
                if ($item instanceof SystemUnit) {
                    return $item->room?->id == $this->room;
                }

                // ComponentParts and Peripheral have room via systemUnit
                if ($item instanceof ComponentParts || $item instanceof Peripheral) {
                    return $item->systemUnit?->room?->id == $this->room;
                }

                return false;
            });
        }

        return $query;
    }


    private function getQrRecords()
    {
        $query = QrGeneration::with('item')->orderByDesc('created_at');

        if ($this->filterModel) {
            $modelClassMap = [
                'SystemUnit' => SystemUnit::class,
                'ComponentParts' => ComponentParts::class,
                'Peripheral' => Peripheral::class,
            ];

            $modelClass = $modelClassMap[$this->filterModel] ?? null;

            if ($modelClass) {
                $query->where('item_type', $modelClass);
            }
        }

        return $query->get();
    }


    private function getActivityLogs()
    {
        $query = Activity::with('causer');

        if ($this->search) {
            $query = $query->where('description', 'like', "%{$this->search}%")
                ->orWhereHas('causer', fn($q) => $q->where('name', 'like', "%{$this->search}%"));
        }

        if ($this->filterModel) {
            $query = $query->where('subject_type', 'like', "%{$this->filterModel}%");
        }

        if ($this->dateFrom) {
            $query = $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query = $query->whereDate('created_at', '<=', $this->dateTo);
        }

        return $query->orderByDesc('created_at')->paginate($this->perPage);
    }

    // Optional: Export to CSV
    public function exportCsv()
    {
        $data = [];

        if ($this->reportType == 'inventory') {
            foreach ($this->getInventoryRecords() as $item) {
                $data[] = [
                    'Type' => class_basename($item),
                    'Name' => $item->name ?? '',
                    'Serial' => $item->serial_number ?? '',
                    'Status' => $item->status ?? '',
                    'Condition' => $item->condition ?? '',
                    'Room' => $item->room->name ?? '',
                ];
            }
        }

        if ($this->reportType == 'qr') {
            foreach ($this->getQrRecords() as $qr) {
                $data[] = [
                    'Item Type' => class_basename($qr->item_type),
                    'Serial' => $qr->item->serial_number ?? '',
                    'Generated At' => $qr->created_at,
                ];
            }
        }

        if ($this->reportType == 'activity') {
            foreach ($this->getActivityLogs() as $log) {
                $props = $log->properties ?? [];
                $data[] = [
                    'User' => $log->causer->name ?? 'System',
                    'Action' => $log->description,
                    'Subject' => class_basename($log->subject_type),
                    'Attributes' => json_encode($props['attributes'] ?? [], JSON_UNESCAPED_UNICODE),
                    'Old' => json_encode($props['old'] ?? [], JSON_UNESCAPED_UNICODE),
                    'Date' => $log->created_at,
                ];
            }
        }

        return Excel::download(new ReportExport($data), 'report.csv');
    }
}
