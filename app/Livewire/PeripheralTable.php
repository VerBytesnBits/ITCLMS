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
use App\Models\Peripheral;

class PeripheralTable extends DataTableComponent
{
    protected $model = Peripheral::class;
    public string $tableName = 'peripheral_table';

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
        Peripheral::whereIn('id', $this->getSelected())->forceDelete();
        $this->clearSelected();

        $this->dispatch('bulk-deleted-success');
    }

    #[On('bulk-junk-confirmed')]
    public function confirmBulkJunk()
    {
        Peripheral::whereIn('id', $this->getSelected())
            ->each(function ($item) {
                $item->update(['status' => 'Junk']);
                $item->delete();
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

            // Part Filter
            SelectFilter::make('Type')
                ->options([
                    '' => 'All Peripheral Type',
                    'Monitor' => 'Monitor',
                    'Keyboard' => 'Keyboard',
                    'Mouse' => 'Mouse',
                    'Speaker' => 'Speaker',
                    'AVR' => 'AVR',
                    'UPS' => 'UPS',
                ])
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('type', $value);
                }),

            // Age Filter (in years)
            NumberFilter::make('Max Age (Years)')
                ->filter(function (Builder $builder, string $value) {
                    $builder->whereDate(
                        'purchase_date',
                        '>=',
                        now()->subYears((int) $value)
                    );
                }),
        ];
    }
    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("System unit id", "system_unit_id")
                ->hideIf(true),
            Column::make("Current unit id", "current_unit_id")
                ->hideIf(true),
            Column::make("Room id", "room_id")
                ->hideIf(true),

            Column::make("Serial number", "serial_number")
                ->sortable(),
            Column::make("Brand", "brand")
                ->sortable(),
            Column::make("Model", "model")
                ->sortable(),

            Column::make("Type", "type")
                ->sortable(),

            Column::make('Status', 'status')
                ->format(function ($value, $row) {
                    $statusColors = StatusConfig::statuses();
                    $colorClass = $statusColors[$row->status] ?? 'bg-gray-100 text-gray-700';

                    return '<span class="px-2 py-1 text-sm font-semibold rounded-full ' . $colorClass . '">' . e($row->status) . '</span>';
                })
                ->html()
                ->sortable(),
            Column::make('Room / PC')
                ->label(function ($row) {
                    $room = $row->room?->name;        // Eloquent relationship loaded
                    $unit = $row->systemUnit?->name; // Eloquent relationship loaded
        
                    if ($room == 'Unassigned' && $unit == 'Unassigned')
                        return '<span class="italic text-gray-400">Unassigned</span>';
                    if ($room && !$unit)
                        return "<span class='px-2 py-1 bg-v-100 text-blue-800 rounded'>$room</span> / No PC";
                    if (!$room && $unit)
                        return "No Room / <span class='px-2 py-1 bg-blue-100 text-blue-800 rounded'>$unit</span>";

                    return "<span class='px-2 py-1 bg-blue-100 text-blue-800 rounded'>$room</span> / <span class='px-2 py-1 bg-blue-100 text-blue-800 rounded'>$unit</span>";
                })
                ->html(),

            // Actions Column
            Column::make('Actions')
                ->label(fn($row) => view('livewire.peripherals.actions', ['row' => $row, 'id' => $row->id,])->render())
                ->html(), // important so HTML renders
        ];
    }

    #[On('refresh-part-table')]
    public function refreshTable()
    {
        $this->resetPage();
    }
}
