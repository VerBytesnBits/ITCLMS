<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class ActivityLogViewer extends Component
{
    use WithPagination;

    public $perPage = 20;
    public $search = '';
    public $filterModel = ''; // optional filter by model type

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterModel()
    {
        $this->resetPage();
    }

    public function render()
    {
        $logs = Activity::with('causer')
            ->when($this->search, function ($query) {
                $query->where('description', 'like', "%{$this->search}%")
                      ->orWhereHas('causer', function ($q) {
                          $q->where('name', 'like', "%{$this->search}%");
                      });
            })
            ->when($this->filterModel, function ($query) {
                $query->where('subject_type', 'like', "%{$this->filterModel}%");
            })
            ->orderByDesc('created_at')
            ->paginate($this->perPage);

        return view('livewire.activity-log-viewer', [
            'logs' => $logs,
        ]);
    }
}
