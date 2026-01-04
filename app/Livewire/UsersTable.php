<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Attributes\On;


class UsersTable extends DataTableComponent
{
    protected $model = User::class;

    public string $tableName = 'user_table';

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setEmptyMessage('No Data Found!');
        $this->setPerPageAccepted([5, 10, 25, 50, 100]);
        $this->setDefaultPerPage(5);
        $this->setQueryStringDisabled();
        $this->setDefaultSort('id', 'desc');
        $this->setBulkActionConfirms([
            'delete',
            'reset'
        ]);
        // $this->setBulkActions([
        //     'exportSelected' => 'Export',
        // ]);
        
        

    }



    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Name", "name")
                ->sortable()
                ->searchable(),
            Column::make("Email", "email")
                ->sortable()
                ->searchable(),
            Column::make("Date of Birth", "date_of_birth")
                ->sortable()
                ->format(
                    fn($value) => $value
                    ? Carbon::parse($value)->format('M d, Y')
                    : '—'
                ),
            Column::make('Roles')
                ->label(fn($row) => view('livewire.users.roles', ['user' => $row]))
                ->html(),

            Column::make('2FA Status', 'google2fa_enabled')
                ->sortable()
                ->format(
                    fn($value) => $value
                    ? '<span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">Enabled</span>'
                    : '<span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full">Disabled</span>'
                )
                ->html(),
            Column::make('Actions')
                ->label(fn($row) => view('livewire.users.actions', ['user' => $row]))
                ->html(),


        ];
    }



    #[On('refresh-user-table')]
    public function refreshTable()
    {
        $this->resetPage();
    }


}
