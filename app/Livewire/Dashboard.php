<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SystemUnit;
use App\Models\ComponentParts;
use App\Models\Peripheral;
use App\Models\Maintenance;
use App\Models\ActivityLog; // optional if you log actions
use Carbon\Carbon;
use DB;

class Dashboard extends Component
{
    public $stats = [];
    public $unitTrends = [];
    public $maintenanceTrends = [];
    public $recentLogs = [];
    public $operationalPercentage;
    public $peripheralPercentage;
    public $componentsBelowThreshold;
    public $peripheralsBelowThreshold;
    public function mount()
    {
        // =============== Summary Stats ===============
        $this->stats = [
            'units' => [
                'Operational' => SystemUnit::where('status', 'Operational')->count(),
                'Non-operational' => SystemUnit::where('status', 'Non-operational')->count(),
            ],
            'parts' => [
                'available' => ComponentParts::where('status', 'available')->count() +
                    Peripheral::where('status', 'available')->count(),
                'defective' => ComponentParts::where('status', 'defective')->count() +
                    Peripheral::where('status', 'defective')->count(),
                'In use' => ComponentParts::whereNotNull('system_unit_id')->count() +
                    Peripheral::whereNotNull('system_unit_id')->count(),
            ],
            'maintenance' => [
                'pending' => Maintenance::where('status', 'pending')->count(),
                'In Progress' => Maintenance::where('status', 'In Progress')->count(),
                'completed' => Maintenance::where('status', 'completed')->count(),
            ],
        ];

        // =============== Trends (Units Defective per Month) ===============
        $this->unitTrends = SystemUnit::select(
            DB::raw("MONTH(created_at) as month"),
            DB::raw("COUNT(*) as total")
        )
            ->where('status', 'Non-operational')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Fill missing months with 0
        $this->unitTrends = $this->fillMonths($this->unitTrends);

        // =============== Trends (Maintenance Reports) ===============
        $this->maintenanceTrends = Maintenance::select(
            DB::raw("MONTH(created_at) as month"),
            DB::raw("COUNT(*) as total")
        )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $this->maintenanceTrends = $this->fillMonths($this->maintenanceTrends);

        // =============== Recent Logs (optional if you log activity) ===============
        // $this->recentLogs = ActivityLog::latest()->take(5)->get();

        $totalComponents = ComponentParts::count();
        $operationalComponents = ComponentParts::where('status', 'available')->count();
        $this->operationalPercentage = $totalComponents ? ($operationalComponents / $totalComponents) * 100 : 0;

        // Peripherals
        $totalPeripherals = Peripheral::count();
        $operationalPeripherals = Peripheral::where('status', 'available')->count();
        $this->peripheralPercentage = $totalPeripherals ? ($operationalPeripherals / $totalPeripherals) * 100 : 0;

        // Items below threshold
        $this->componentsBelowThreshold = ComponentParts::where('status', '!=', 'available')
            ->get(['id', 'part', 'status']); // Only select needed columns

        $this->peripheralsBelowThreshold = Peripheral::where('status', '!=', 'available')
            ->get(['id', 'type', 'status']); // Only select needed columns

    }

    private function fillMonths($data)
    {
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = $data[$i] ?? 0;
        }
        return $months;
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'operationalPercentage' => $this->operationalPercentage,
            'peripheralPercentage' => $this->peripheralPercentage,
            'componentsBelowThreshold' => $this->componentsBelowThreshold,
            'peripheralsBelowThreshold' => $this->peripheralsBelowThreshold,
        ]);

    }
}
