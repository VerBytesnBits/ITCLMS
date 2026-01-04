<?php

namespace App\Livewire;

use App\Models\ComponentParts;
use App\Models\Room;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\NumberFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Support\StatusConfig;
use Livewire\Attributes\On;



class ComponentPartsTable extends DataTableComponent
{
    protected $model = ComponentParts::class;
    public string $tableName = 'component_parts_table';
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
        ComponentParts::whereIn('id', $this->getSelected())->forceDelete();
        $this->clearSelected();

        $this->dispatch('bulk-deleted-success');
    }

    #[On('bulk-junk-confirmed')]
    public function confirmBulkJunk()
    {
        ComponentParts::whereIn('id', $this->getSelected())
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
            SelectFilter::make('Part')
                ->options([
                    '' => 'All Parts',
                    'RAM' => 'RAM',
                    'Storage' => 'Storage',
                    'Motherboard' => 'Motherboard',
                    'CPU' => 'CPU',
                    'GPU' => 'GPU',
                    'Casing' => 'Casing',
                ])
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('part', $value);
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
            Column::make('ID', 'id')
                ->sortable(),

            Column::make('Serial Number', 'serial_number')
                ->searchable()
                ->sortable(),

            Column::make('Brand', 'brand')
                ->searchable()
                ->sortable(),

            Column::make('Model', 'model')
                ->searchable()
                ->sortable(),

            Column::make('Part', 'part')
                ->searchable()
                ->sortable(),




            Column::make('Status', 'status')
                ->format(function ($value, $row) {
                    // Get status check
                    $check = $row->checkOperationalStatus();
                    $missing = $check['missing'];

                    // Color mapping (you can keep StatusConfig if needed)
                    $statusColors = [
                        'Operational' => 'bg-green-100 text-green-700',
                        'Non-operational' => 'bg-red-100 text-red-700',
                    ];

                    if ($check['status'] === 'Operational') {
                        $colorClass = $statusColors['Operational'];
                        return '<span class="px-2 py-1 text-sm font-semibold rounded-full ' . $colorClass . '">Operational</span>';
                    }

                    // Non-operational → render missing components + peripherals
                    $componentsHtml = '';
                    foreach ($missing['components'] as $item) {
                        $componentsHtml .= '<span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs flex items-center gap-1">
                <flux:icon.triangle-alert variant="micro" /> ' . e($item) . '</span> ';
                    }

                    $peripheralsHtml = '';
                    foreach ($missing['peripherals'] as $item) {
                        $peripheralsHtml .= '<span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs flex items-center gap-1">
                <flux:icon.triangle-alert variant="micro" /> ' . e($item) . '</span> ';
                    }

                    return '<div class="flex flex-wrap gap-1 justify-center">' . $componentsHtml . $peripheralsHtml . '</div>';
                })
                ->html()       // Important for rendering badges and HTML
                ->sortable(),  // Still sortable by your 'status' column




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
            Column::make("System unit id", "system_unit_id")
                ->sortable()
                ->hideIf(true),
            Column::make("Current unit id", "current_unit_id")
                ->sortable()
                ->hideIf(true),
            Column::make("Room id", "room_id")
                ->sortable()
                ->hideIf(true),

            // Actions Column
            Column::make('Actions')
                ->label(fn($row) => view('livewire.components-part.actions', ['row' => $row, 'id' => $row->id,])->render())
                ->html(), // important so HTML renders
        ];
    }


    #[On('refresh-part-table')]
    public function refreshTable()
    {
        $this->resetPage();
    }
}

