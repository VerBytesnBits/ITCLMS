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

    public function mount()
    {
        // =============== Summary Stats ===============
        $this->stats = [
            'units' => [
                'Operational' => SystemUnit::where('condition', 'Operational')->count(),
                'defective' => SystemUnit::where('status', 'defective')->count(),
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
            ->where('status', 'defective')
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
        return view('livewire.dashboard');
    }
}
