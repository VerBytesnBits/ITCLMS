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

#[Layout('components.layouts.app', ['title' => 'Peripheral'])]
class PeripheralIndex extends Component
{
    use WithPagination, HasInventorySummary;

    #[Url(as: 'modal')]
    public ?string $modal = null;

    #[Url(as: 'id')]
    public ?int $id = null;

    #[Url(as: 'q')]
    public string $query = ''; // Search/filter text

    #[Url(as: 'tab')]
    public ?string $tab = null; // Active tab

    public int $perPage = 10; // Items per page
    public string $sortColumn = 'available';
    public string $sortDirection = 'asc';
    public $lowStockThreshold = 5;
    /**
     * Inventory summary grouped by type
     */
    public function getPeripheralSummaryProperty()
    {
        return $this->getInventorySummary(
            Peripheral::class,
            'type', // group peripherals by type (e.g., Mouse, AVR, Printer)
            ['brand', 'model'],
            $this->sortColumn,
            $this->sortDirection
        );
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

    public function render()
    {
        $peripherals = Peripheral::with(['systemUnit', 'room'])
            ->when($this->tab && $this->tab !== 'All', function ($query) {
                $query->where('type', $this->tab);
            })
            ->when($this->query, function ($query) {
                $query->where(function ($q) {
                    $q->where('serial_number', 'like', '%' . $this->query . '%')
                        ->orWhere('brand', 'like', '%' . $this->query . '%')
                        ->orWhere('model', 'like', '%' . $this->query . '%');
                });
            })
            ->paginate($this->perPage);

        return view('livewire.peripherals.peripheral-index', [
            'peripherals' => $peripherals,
            'summary' => $this->peripheralSummary,
        ]);
    }
}
