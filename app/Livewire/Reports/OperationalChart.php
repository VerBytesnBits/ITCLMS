<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Room;
use App\Models\SystemUnit;

class OperationalChart extends Component
{
    public $chartData = [];

    public function mount()
    {
        $this->loadChartData();
    }

    public function loadChartData()
    {
        $labs = Room::with('systemUnits')->get();

        $this->chartData = $labs->map(function ($lab) {
            $operational = $lab->systemUnits->where('status', 'Operational')->count();
            $nonOperational = $lab->systemUnits->where('status', '!=', 'Operational')->count();

            return [
                'lab' => $lab->name,
                'operational' => $operational,
                'non_operational' => $nonOperational,
            ];
        });
    }

    public function render()
    {
        return view('livewire.reports.operational-chart');
    }
}
