<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SystemUnit;
use App\Models\ComponentParts;
use App\Models\Peripheral;
use App\Models\Maintenance;
use App\Traits\HasInventorySummary; 
use Carbon\Carbon;
use DB;
use Spatie\Activitylog\Models\Activity;

class Dashboard extends Component
{
    use HasInventorySummary;

    public $stats = [];
    public $unitTrends = [];
    public $maintenanceTrends = [];
   
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
    public $recentLogs;
    public function mount()
    {
        $this->recentLogs = Activity::latest()->take(5)->get();
        $this->totalUnits = SystemUnit::count();

        
        $this->totalComponents = ComponentParts::count();

       
        $this->totalPeripherals = Peripheral::count();

        
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
           
        ];


       
        $this->unitTrends = $this->fillMonths(
            SystemUnit::select(DB::raw("MONTH(created_at) as month"), DB::raw("COUNT(*) as total"))
                ->where('status', 'Non-operational')
                ->whereYear('created_at', Carbon::now()->year)
                ->groupBy('month')
                ->pluck('total', 'month')
                ->toArray()
        );

       

        
        $totalComponents = ComponentParts::count();
        $operationalComponents = ComponentParts::where('status', 'available')->count();
        $this->operationalPercentage = $totalComponents ? ($operationalComponents / $totalComponents) * 100 : 0;

        $totalPeripherals = Peripheral::count();
        $operationalPeripherals = Peripheral::where('status', 'available')->count();
        $this->peripheralPercentage = $totalPeripherals ? ($operationalPeripherals / $totalPeripherals) * 100 : 0;

    
        $componentsSummary = $this->getInventorySummary(
            ComponentParts::class,
            'part',           
            ['model', 'brand', 'type', 'capacity'] 
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

        
        $peripheralsSummary = $this->getInventorySummary(
            Peripheral::class,
            'type',          
            ['model', 'brand'] 
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
