<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SystemUnit;
use App\Models\ComponentParts;
use App\Models\Peripheral;
use App\Models\Maintenance;
use App\Traits\HasInventorySummary; // <-- include the trait
use Carbon\Carbon;
use DB;

class Dashboard extends Component
{
    use HasInventorySummary; // <-- use the trait

    public $stats = [];
    public $unitTrends = [];
    public $maintenanceTrends = [];
    public $recentLogs = [];
    public $operationalPercentage;
    public $peripheralPercentage;
    public $componentsBelowThreshold;
    public $peripheralsBelowThreshold;
    public $componentsOutOfStock;
    public $peripheralsOutOfStock;
    public $threshold = 5;
    public $totalUnits;
    public $totalComponents;
    public $totalPeripherals;
    public function mount()
    {
        $this->totalUnits = SystemUnit::count();

        // Total Components
        $this->totalComponents = ComponentParts::count();

        // Total Peripherals
        $this->totalPeripherals = Peripheral::count();

        // =============== Summary Stats ===============
        $this->stats = [
            'units' => [
                'Operational' => SystemUnit::where('status', 'Operational')->count(),
                'Non-operational' => SystemUnit::where('status', 'Non-operational')->count(),
            ],
            'components' => [
                'available' => ComponentParts::where('status', 'available')->count(),
                'defective' => ComponentParts::where('status', 'defective')->count(),
                'In use' => ComponentParts::whereNotNull('system_unit_id')->count(),
            ],
            'peripherals' => [
                'available' => Peripheral::where('status', 'available')->count(),
                'defective' => Peripheral::where('status', 'defective')->count(),
                'In use' => Peripheral::whereNotNull('system_unit_id')->count(),
            ],
            'maintenance' => [
                'pending' => Maintenance::where('status', 'pending')->count(),
                'in_progress' => Maintenance::where('status', 'In Progress')->count(),
                'completed' => Maintenance::where('status', 'completed')->count(),
            ],
        ];


        // =============== Trends ===============
        $this->unitTrends = $this->fillMonths(
            SystemUnit::select(DB::raw("MONTH(created_at) as month"), DB::raw("COUNT(*) as total"))
                ->where('status', 'Non-operational')
                ->whereYear('created_at', Carbon::now()->year)
                ->groupBy('month')
                ->pluck('total', 'month')
                ->toArray()
        );

        $this->maintenanceTrends = $this->fillMonths(
            Maintenance::select(DB::raw("MONTH(created_at) as month"), DB::raw("COUNT(*) as total"))
                ->whereYear('created_at', Carbon::now()->year)
                ->groupBy('month')
                ->pluck('total', 'month')
                ->toArray()
        );

        // =============== Operational Percentages ===============
        $totalComponents = ComponentParts::count();
        $operationalComponents = ComponentParts::where('status', 'available')->count();
        $this->operationalPercentage = $totalComponents ? ($operationalComponents / $totalComponents) * 100 : 0;

        $totalPeripherals = Peripheral::count();
        $operationalPeripherals = Peripheral::where('status', 'available')->count();
        $this->peripheralPercentage = $totalPeripherals ? ($operationalPeripherals / $totalPeripherals) * 100 : 0;

        // =============== Components Low / Out of Stock using Trait ===============
        $componentsSummary = $this->getInventorySummary(
            ComponentParts::class,
            'part',            // group by part
            ['model', 'brand', 'type', 'capacity'] // variant columns to distinguish items
        );

        $lowStock = 0;
        $outOfStock = 0;
        foreach ($componentsSummary as $part => $variants) {
            foreach ($variants as $variant) {
                if ($variant['available'] > 0 && $variant['available'] < $this->threshold) {
                    $lowStock++;
                } elseif ($variant['available'] == 0) {
                    $outOfStock++;
                }
            }
        }
        $this->componentsBelowThreshold = $lowStock;
        $this->componentsOutOfStock = $outOfStock;

        // =============== Peripherals Low / Out of Stock using Trait ===============
        $peripheralsSummary = $this->getInventorySummary(
            Peripheral::class,
            'type',           // group by type
            ['model', 'brand'] // variant columns
        );

        $lowStockPeripherals = 0;
        $outOfStockPeripherals = 0;
        foreach ($peripheralsSummary as $type => $variants) {
            foreach ($variants as $variant) {
                if ($variant['available'] > 0 && $variant['available'] < $this->threshold) {
                    $lowStockPeripherals++;
                } elseif ($variant['available'] == 0) {
                    $outOfStockPeripherals++;
                }
            }
        }
        $this->peripheralsBelowThreshold = $lowStockPeripherals;
        $this->peripheralsOutOfStock = $outOfStockPeripherals;
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
            'componentsOutOfStock' => $this->componentsOutOfStock,
            'peripheralsBelowThreshold' => $this->peripheralsBelowThreshold,
            'peripheralsOutOfStock' => $this->peripheralsOutOfStock,
        ]);
    }
}
