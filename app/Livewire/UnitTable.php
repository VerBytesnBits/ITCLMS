<?php

namespace App\Livewire;

use App\Models\Room;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\NumberFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Support\StatusConfig;
use Livewire\Attributes\On;
use App\Models\SystemUnit;

class UnitTable extends DataTableComponent
{
    protected $model = SystemUnit::class;

    public string $tableName = 'unit_table';
    public $modal = null;
    public $selectedId = null;

    #[On(event: 'open-view-modal')]
    public function openViewModal($id)
    {
        $this->selectedId = $id;
        $this->modal = 'view';
    }
    #[On(event: 'open-edit-modal')]
    public function openEditModal($id)
    {
        $this->selectedId = $id;
        $this->modal = 'edit';
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchEnabled()
            ->setDefaultSort('id', 'desc')
            ->setQueryStringDisabled()
            ->setPerPageAccepted([5, 10, 25, 50])
            ->setPerPage(10);

    }



    public function bulkActions(): array
    {
        return [
            'bulkDelete' => 'Delete',
            'bulkJunk' => 'Move to Junk',
        ];
    }


    public function bulkDelete()
    {
        if (empty($this->getSelected())) {
            $this->dispatch(
                'swal',
                icon: 'info',
                title: 'No rows selected'
            );
            return;
        }

        $this->dispatch('bulk-delete-confirm');
    }


    public function bulkJunk()
    {
        if (empty($this->getSelected())) {
            $this->dispatch(
                'swal',
                icon: 'info',
                title: 'No rows selected'
            );
            return;
        }
        $this->dispatch('bulk-junk-confirm');
    }



    #[On('bulk-delete-confirmed')]
    public function confirmBulkDelete()
    {
        SystemUnit::whereIn('id', $this->getSelected())->forceDelete();
        $this->clearSelected();

        $this->dispatch('bulk-deleted-success');
    }

    #[On('bulk-junk-confirmed')]
    public function confirmBulkRepair()
    {
        SystemUnit::whereIn('id', $this->getSelected())
            ->each(function ($item) {
                $item->update(['status' => 'Non-operational']);

            });


        $this->clearSelected();

        $this->dispatch('bulk-junk-success');
    }



    protected function afterBulkAction(string $message, string $icon = 'success')
    {
        $this->clearSelected();

        $this->dispatch('swal', [
            'icon' => $icon,
            'title' => $message,
            'timer' => 2000,
        ]);

        $this->resetPage();
    }




    public function filters(): array
    {
        return [

            // Room Filter
            SelectFilter::make('Room')
                ->options(
                    ['' => 'All Rooms'] +
                    Room::orderBy('name')
                        ->pluck('name', 'id')
                        ->toArray()
                )
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('room_id', $value);
                }),





            SelectFilter::make('Status')
                ->options([
                    '' => 'All Status',          // default
                    'Operational' => 'Operational',
                    'Non-operational' => 'Non-operational',
                    'Needs Repair' => 'Needs Repair',
                ])
                ->filter(function (Builder $builder, string $value) {
                    if ($value) {
                        $builder->where('status', $value); // simple direct filter
                    }
                }),




        ];
    }
    public function builder(): Builder
    {
        return SystemUnit::query()
            ->with(['components', 'peripherals']); // ⚡ MUST eager load
    }


    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Name", "name")
                ->sortable(),
            Column::make('Room')
                ->label(function ($row) {
                    $room = $row->room?->name;

                    return $room;
                })
                ->html(),
            Column::make("Serial number", "serial_number")
                ->sortable(),


            Column::make('Status', 'status')
                ->format(function ($value, $row) {
                    $check = $row->checkOperationalStatus();
                    $missing = $check['missing'];

                    // Get color classes from StatusConfig
                    $statusColors = StatusConfig::statuses(); // e.g., ['Operational' => 'bg-green-100 text-green-700', 'Non-operational' => 'bg-red-100 text-red-700']
                    $colorClass = $statusColors[$check['status']] ?? 'bg-gray-100 text-gray-700';

                    // Status badge
                    $statusBadge = '<span class="px-2 py-1 text-sm font-semibold rounded-full ' . $colorClass . '">' . e($check['status']) . '</span>';

                    // Missing items
                    $allMissing = collect($missing['components'])
                        ->merge($missing['peripherals'])
                        ->map(fn($item) => '<span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-red-700" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.681-1.36 3.446 0l7.026 12.5c.75 1.337-.213 3-1.723 3H2.954c-1.51 0-2.473-1.663-1.723-3l7.026-12.5zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-4a1 1 0 00-.993.883L9 10v2a1 1 0 001.993.117L11 12v-2a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                ' . e($item) . '</span>')
                        ->implode(' ');

                    // Combine status badge + missing items
                    return '<div class="flex flex-col gap-1 items-start">'
                        . $statusBadge
                        . ($allMissing ? '<div class="flex flex-wrap gap-1 justify-center mt-1">' . $allMissing . '</div>' : '')
                        . '</div>';
                })
                ->html()
                ->sortable(),


            Column::make("Room id", "room_id")
                ->hideIf(true),
            Column::make("Qr code path", "qr_code_path")
                ->hideIf(true),

            Column::make('Actions')
                ->label(fn($row) => view('livewire.system-units.actions', [
                    'row' => $row,
                    'id' => $row->id,
                    'qr_code_path' => $row->qr_code_path,
                    'serial_number' => $row->serial_number,
                ])->render())
                ->html(),


        ];
    }


    #[On('refresh-part-table')]
    public function refreshTable()
    {
        $this->resetPage();
    }
}
