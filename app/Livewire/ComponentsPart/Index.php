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
use App\Support\StatusConfig;
use App\Models\Room;
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

    #[Url(as: 'room')]
    public ?int $roomId = null;

    #[Url(as: 'age')]
    public string $age = '';


    public int $perPage = 10; // Items per page
    public string $sortColumn = 'available';
    public string $sortDirection = 'asc';
    public $lowStockThreshold = 5;
    public function placeholder()
    {
        return view('components.skeletons.skeleton');
    }

    
    public function getComponentSummaryProperty()
    {
        $filters = [];

        if ($this->roomId) {
            $filters['room_id'] = $this->roomId;
        }

        if ($this->age) {
            $filters['__age'] = $this->age;
        }

        return $this->getInventorySummary(
            ComponentParts::class,
            'part',
            ['brand', 'model', 'speed', 'capacity', 'type'],
            $this->sortColumn,
            $this->sortDirection,
            $filters
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
        $labs = Room::all();
        $statusColors = StatusConfig::statuses();
        $components = ComponentParts::with(['systemUnit', 'room'])
            ->when($this->roomId, function ($q) {
                $q->whereHas('systemUnit', function ($unit) {
                    $unit->where('room_id', $this->roomId);
                });
            })
            ->when($this->age, function ($q) {
                // applyFilters logic here
                if ($this->age === 'new') {
                    $q->where(function ($sub) {
                        $sub->where('warranty_expires_at', '>=', now())
                            ->orWhere('purchase_date', '>=', now()->subYear());
                    });
                } elseif (preg_match('/^older_(\d+)(month|months|year|years)$/', $this->age, $matches)) {
                    $amount = (int) $matches[1];
                    $unit = rtrim($matches[2], 's');
                    $q->where('purchase_date', '<', now()->sub($unit, $amount));
                }
            })
            ->when($this->tab && $this->tab !== 'All', function ($q) {
                $q->where('part', $this->tab);
            })
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('serial_number', 'like', '%' . $this->search . '%')
                        ->orWhere('part', 'like', '%' . $this->search . '%')
                        ->orWhere('model', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate($this->perPage);


        return view('livewire.components-part.index', [
            'statusColors' => $statusColors,
            'components' => $components,
            'summary' => $this->componentSummary,
            'labs' => $labs
        ]);
    }


}
