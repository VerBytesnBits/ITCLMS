<?php

namespace App\Livewire\SystemUnits;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;
use App\Models\SystemUnit;

class ActivityLog extends Component
{
    use WithPagination;

    public $systemUnitId;
    public $perPage = 5;

    protected $listeners = ['view-systemunit-history' => 'loadHistory'];

    public function loadHistory($systemUnitId)
    {
        $this->systemUnitId = $systemUnitId;
        $this->resetPage(); // reset pagination if unit changes
    }

    public function render()
    {
        $history = Activity::where('subject_type', SystemUnit::class)
            ->where('subject_id', $this->systemUnitId)
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.system-units.activity-log', compact('history'));
    }
}
