<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use App\Models\ComponentParts;
use App\Models\Peripheral;
use Illuminate\Support\Facades\DB;

class Overview extends Component
{
    public $componentSummary = [];
    public $peripheralSummary = [];

    public function mount()
    {
        // Components with specs
        $this->componentSummary = ComponentParts::select(
            DB::raw("CONCAT(part, ' - ', COALESCE(brand,''), ' ', COALESCE(model,''), ' ', COALESCE(capacity,''), ' ', COALESCE(type,'')) as description"),
            DB::raw('COUNT(*) as total'),
            DB::raw("SUM(CASE WHEN status = 'Available' THEN 1 ELSE 0 END) as available"),
            DB::raw("SUM(CASE WHEN status = 'In Use' THEN 1 ELSE 0 END) as in_use"),
            DB::raw("SUM(CASE WHEN status = 'Defective' THEN 1 ELSE 0 END) as defective"),
            DB::raw("SUM(CASE WHEN status = 'Under Maintenance' THEN 1 ELSE 0 END) as maintenance"),
            DB::raw("SUM(CASE WHEN status = 'Junk' THEN 1 ELSE 0 END) as junk"),
            DB::raw("SUM(CASE WHEN status = 'Salvage' THEN 1 ELSE 0 END) as salvage")
        )
        ->groupBy('description')
        ->get();

        // Peripherals with specs
        $this->peripheralSummary = Peripheral::select(
            DB::raw("CONCAT(type, ' - ', COALESCE(brand,''), ' ', COALESCE(model,'')) as description"),
            DB::raw('COUNT(*) as total'),
            DB::raw("SUM(CASE WHEN status = 'Available' THEN 1 ELSE 0 END) as available"),
            DB::raw("SUM(CASE WHEN status = 'In Use' THEN 1 ELSE 0 END) as in_use"),
            DB::raw("SUM(CASE WHEN status = 'Defective' THEN 1 ELSE 0 END) as defective"),
            DB::raw("SUM(CASE WHEN status = 'Under Maintenance' THEN 1 ELSE 0 END) as maintenance"),
            DB::raw("SUM(CASE WHEN status = 'Junk' THEN 1 ELSE 0 END) as junk"),
            DB::raw("SUM(CASE WHEN status = 'Salvage' THEN 1 ELSE 0 END) as salvage")
        )
        ->groupBy('description')
        ->get();
    }

    public function render()
    {
        return view('livewire.inventory.overview');
    }
}
