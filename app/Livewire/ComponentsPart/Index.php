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
use App\Livewire\ComponentPartsTable;

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
            ['part', 'brand', 'model', 'speed', 'capacity', 'type'],
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
    #[On(event: 'open-view-modal')]
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
    #[On(event: 'open-edit-modal')]
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
    #[On('item-deleted')]
    public function handleComponentChange()
    {
        $this->resetPage();
        $this->dispatch('refresh-part-table')
            ->to(ComponentPartsTable::class);
    }

    public function deleteComponent($id)
    {
        ComponentParts::findOrFail($id)->delete();
        $this->dispatch('swal', toast: true, icon: 'success', title: 'Component deleted successfully', timer: 3000);
        $this->dispatch('componentDeleted');
    }
    public $selectedComponents = [];
    public $selectAll = false;

    public function updatedSelectAll($value)
    {
        if ($value) {
            // Get the currently rendered items (already paginated)
            $this->selectedComponents = $this->getRenderedComponentsIds();
        } else {
            $this->selectedComponents = [];
        }
    }
    protected function getRenderedComponentsIds()
    {
        return ComponentParts::query()
            ->when($this->roomId, function ($q) {
                $q->whereHas('systemUnit', fn($unit) => $unit->where('room_id', $this->roomId));
            })
            ->when($this->age, function ($q) {
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
            ->when($this->tab && $this->tab !== 'All', fn($q) => $q->where('part', $this->tab))
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('serial_number', 'like', '%' . $this->search . '%')
                        ->orWhere('part', 'like', '%' . $this->search . '%')
                        ->orWhere('model', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate($this->perPage)
            ->pluck('id')
            ->toArray();
    }


    #[On('confirm-bulk-delete')]
    public function bulkDelete($payload)
    {
        if ($payload['model'] !== 'ComponentParts')
            return;

        match ($payload['action']) {
            'delete' => ComponentParts::whereIn('id', $this->selectedComponents)->forceDelete(),
            'junk' => ComponentParts::whereIn('id', $this->selectedComponents)->each(function ($item) {
                    $item->update(['status' => 'Junk']);
                    $item->delete();
                }),
            default => null,
        };

        $this->reset(['selectedComponents', 'selectAll']);
        $this->dispatch('swal', [
            'icon' => $payload['action'] === 'delete' ? 'success' : 'warning',
            'title' => $payload['action'] === 'delete'
                ? 'Selected items permanently deleted.'
                : 'Selected items moved to Junk.',
            'timer' => 2000,
        ]);

        $this->dispatch('refresh-part-table')
            ->to(ComponentPartsTable::class);
    }
    public string $scannedCode = '';
    public ?ComponentParts $scannedComponents = null;

    public function findComponentByBarcode()
    {
        if (empty($this->scannedCode))
            return;

        $code = trim($this->scannedCode);

        $components = ComponentParts::where('serial_number', $code)
            ->orWhereRaw("REPLACE(REPLACE(barcode_path, 'storage/barcodes/', ''), '.png', '') = ?", [$code])
            ->first();

        if ($components) {
            $this->openViewModal($components->id);
        } else {
            $this->dispatch('swal', icon: 'error', title: 'Component not found');
        }

        $this->reset('scannedCode');
        $this->dispatch('scan-complete');
    }

    public function render()
    {
        $labs = Room::all();
        $statusColors = StatusConfig::statuses();

        $components = ComponentParts::with(['systemUnit', 'room'])
            ->when($this->roomId, function ($q) {
                $q->where(function ($sub) {

                    $sub->whereHas('systemUnit', fn($unit) => $unit->where('room_id', $this->roomId))
                        ->orWhere('room_id', $this->roomId);
                });
            })
            ->when($this->age, function ($q) {
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
            ->when($this->tab && $this->tab !== 'All', fn($q) => $q->where('part', $this->tab))
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('serial_number', 'like', '%' . $this->search . '%')
                        ->orWhere('part', 'like', '%' . $this->search . '%')
                        ->orWhere('model', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('id', 'desc') // Use valid column to avoid SQL error
            ->paginate($this->perPage);

        return view('livewire.components-part.index', [
            'statusColors' => $statusColors,
            'components' => $components,
            'summary' => $this->componentSummary,
            'labs' => $labs,
        ]);
    }



}
