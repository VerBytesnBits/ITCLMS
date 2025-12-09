<?php

namespace App\Livewire\Peripherals;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Peripheral;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use App\Traits\HasInventorySummary;
use Livewire\Attributes\Lazy;
use App\Support\StatusConfig;
use App\Models\Room;

#[Lazy]
#[Layout('components.layouts.app', ['title' => 'Peripherals'])]
class PeripheralIndex extends Component
{
    use WithPagination, HasInventorySummary;

    #[Url(as: 'modal')]
    public ?string $modal = null;

    #[Url(as: 'id')]
    public ?int $id = null;

    #[Url(as: 'q')]
    public string $search = ''; // Search/filter text

    #[Url(as: 'tab')]
    public ?string $tab = null; // Active tab
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
    /**
     * Inventory summary grouped by type
     */
    public function getPeripheralSummaryProperty()
    {
        $filters = [];

        if ($this->roomId) {
            $filters['room_id'] = $this->roomId;
        }

        if ($this->age) {
            $filters['__age'] = $this->age;
        }
        return $this->getInventorySummary(
            Peripheral::class,
            ['type','model'],
            [
                'brand',
                'model',
                'dpi',
                'wattage',
                'resolution',
                'capacity_va',
            ],
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
        $this->resetPage();
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

    #[On('peripheralCreated')]
    #[On('peripheralUpdated')]
    #[On('peripheralDeleted')]
    #[On('item-deleted')]
    public function handlePeripheralChange()
    {
        $this->resetPage();
    }

    public function deletePeripheral($id)
    {
        Peripheral::findOrFail($id)->delete();
        $this->dispatch('swal', toast: true, icon: 'success', title: 'Peripheral deleted successfully', timer: 3000);
        $this->dispatch('peripheralDeleted');
    }


    public string $scannedCode = '';
    public ?Peripheral $scannedPeripheral = null;

    public function findPeripheralByBarcode()
    {
        if (empty($this->scannedCode))
            return;

        $code = trim($this->scannedCode);

        $peripheral = Peripheral::where('serial_number', $code)
            ->orWhereRaw("REPLACE(REPLACE(barcode_path, 'storage/barcodes/', ''), '.png', '') = ?", [$code])
            ->first();

        if ($peripheral) {
            $this->openViewModal($peripheral->id);
        } else {
            $this->dispatch('swal', icon: 'error', title: 'Peripheral not found');
        }

        $this->reset('scannedCode');
        $this->dispatch('scan-complete');
    }



    public $selectedPeripherals = [];
    public $selectAll = false;

    public function updatedSelectAll($value)
    {
        if ($value) {
            // Get the currently rendered items (already paginated)
            $this->selectedPeripherals = $this->getRenderedPeripheralsIds();
        } else {
            $this->selectedPeripherals = [];
        }
    }
    protected function getRenderedPeripheralsIds()
    {
        return Peripheral::query()
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
            ->when($this->tab && $this->tab !== 'All', fn($q) => $q->where('type', $this->tab))
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('serial_number', 'like', '%' . $this->search . '%')
                        ->orWhere('type', 'like', '%' . $this->search . '%')
                        ->orWhere('model', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate($this->perPage)
            ->pluck('id')
            ->toArray();
    }


    protected $listeners = ['confirm-bulk-delete' => 'bulkDelete'];
    public function bulkDelete($payload)
    {
        if ($payload['model'] !== 'Peripherals')
            return;

        match ($payload['action']) {
            'delete' => Peripheral::whereIn('id', $this->selectedPeripherals)->forceDelete(),
            'junk' => Peripheral::whereIn('id', $this->selectedPeripherals)->each(function ($item) {
                    $item->update(['status' => 'Junk']);
                    $item->delete();
                }),
            default => null,
        };

        $this->reset(['selectedPeripherals', 'selectAll']);
        $this->dispatch('swal', [
            'icon' => $payload['action'] === 'delete' ? 'success' : 'warning',
            'title' => $payload['action'] === 'delete'
                ? 'Selected items permanently deleted.'
                : 'Selected items moved to Junk.',
            'timer' => 2000,
        ]);

        $this->dispatch('$refresh');
    }

    public function render()
    {
        $labs = Room::all();
        $statusColors = StatusConfig::statuses();

        $peripherals = Peripheral::with(['systemUnit', 'room'])
            ->when($this->roomId, function ($q) {
                $q->where(function ($sub) {
                    // Include peripherals that are either:
                    // 1. linked to a system unit in the selected room
                    // 2. directly assigned to the selected room
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
            ->when($this->tab && $this->tab !== 'All', fn($q) => $q->where('type', $this->tab))
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('serial_number', 'like', '%' . $this->search . '%')
                        ->orWhere('type', 'like', '%' . $this->search . '%')
                        ->orWhere('brand', 'like', '%' . $this->search . '%')
                        ->orWhere('model', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('id', 'asc')
            ->paginate($this->perPage);

        return view('livewire.peripherals.peripheral-index', [
            'statusColors' => $statusColors,
            'peripherals' => $peripherals,
            'summary' => $this->peripheralSummary,
            'labs' => $labs,
        ]);
    }


}
