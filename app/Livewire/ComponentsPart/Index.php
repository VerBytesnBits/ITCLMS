<?php

namespace App\Livewire\ComponentsPart;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ComponentParts;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use App\Traits\HasInventorySummary;
use Livewire\Attributes\Lazy;

#[Lazy]
#[Layout('components.layouts.app', ['title' => 'Components'])]
class Index extends Component
{
    use WithPagination, HasInventorySummary;

    #[Url(as: 'modal')]
    public ?string $modal = null;

    #[Url(as: 'id')]
    public ?int $id = null;

    #[Url(as: 'q')]
    public string $search = ''; // For search/filtering

    #[Url(as: 'tab')]
    public ?string $tab = null; // default tab

    public int $perPage = 10; // Items per page
    public string $sortColumn = 'available';
    public string $sortDirection = 'asc';
    public $lowStockThreshold = 5;
    public function placeholder()
    {
        return view('components.skeletons.skeleton');
    }

    /**
     * Summary grouped by part
     */
    // public function getComponentSummaryProperty()
    // {
    //     $summary = ComponentParts::select(
    //         'part',
    //         DB::raw("CONCAT(
    //             COALESCE(brand,''), ' ',
    //             COALESCE(model,''), ' ',
    //             COALESCE(speed,''), ' ',
    //             COALESCE(capacity,''), ' ',
    //             COALESCE(type,'')) as description"),
    //         DB::raw('COUNT(*) as total'),
    //         DB::raw("SUM(CASE WHEN status = 'Available' THEN 1 ELSE 0 END) as available"),
    //         DB::raw("SUM(CASE WHEN status = 'In Use' THEN 1 ELSE 0 END) as in_use"),
    //         DB::raw("SUM(CASE WHEN status = 'Defective' THEN 1 ELSE 0 END) as defective"),
    //         DB::raw("SUM(CASE WHEN status = 'Under Maintenance' THEN 1 ELSE 0 END) as maintenance"),
    //         DB::raw("SUM(CASE WHEN status = 'Junk' THEN 1 ELSE 0 END) as junk"),
    //         DB::raw("SUM(CASE WHEN status = 'Salvaged' THEN 1 ELSE 0 END) as salvage")
    //     )
    //         ->groupBy('part', 'description')
    //         ->orderBy('part')
    //         ->get();

    //     // Sorting logic
    //     if ($this->sortColumn && $this->sortDirection) {
    //         $summary = $summary->sortBy(function ($item) {
    //             return $item->{$this->sortColumn};
    //         });

    //         if ($this->sortDirection === 'desc') {
    //             $summary = $summary->reverse();
    //         }
    //     }

    //     return $summary->groupBy('part')->toArray();
    // }



    public function getComponentSummaryProperty()
    {
        return $this->getInventorySummary(
            ComponentParts::class,
            'part',
            ['brand', 'model', 'speed', 'capacity', 'type'],
            $this->sortColumn,
            $this->sortDirection
        );
    }


    public function updatedSearch()
    {

        $this->resetPage(); // Also reset pagination
    }

    public function updatedTab()
    {
        $this->resetPage(); // reset pagination when switching tabs
    }

    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function openViewModal($id)
    {
        $this->id = $id;
        $this->modal = 'view';
    }

    public function openCreateModal()
    {
        $this->id = null;
        $this->modal = 'create';
    }

    public function openEditModal($id)
    {
        $this->id = $id;
        $this->modal = 'edit';
    }

    #[On('closeModal')]
    public function closeModal()
    {
        $this->modal = null;
        $this->id = null;
    }

    #[On('componentCreated')]
    #[On('componentUpdated')]
    #[On('componentDeleted')]
    public function handleComponentChange()
    {
        $this->resetPage();
    }

    public function deleteComponent($id)
    {
        ComponentParts::findOrFail($id)->delete();
        $this->dispatch('swal', toast: true, icon: 'success', title: 'Component deleted successfully', timer: 3000);
        $this->dispatch('componentDeleted');
    }

    public function render()
    {
        $components = ComponentParts::with(['systemUnit', 'room'])
            ->when($this->tab && $this->tab !== 'All', function ($search) {
                $search->where('part', $this->tab);
            })

            ->when($this->search, function ($search) {
                $search->where(function ($q) {
                    $q->where('serial_number', 'like', '%' . $this->search . '%')
                        ->orWhere('part', 'like', '%' . $this->search . '%')
                        ->orWhere('model', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate($this->perPage);

        return view('livewire.components-part.index', [
            'components' => $components,
            'summary' => $this->componentSummary,
        ]);
    }

}
