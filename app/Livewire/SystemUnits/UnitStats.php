<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Traits\SystemUnitQueryTrait;

class UnitStats extends Component
{
    use SystemUnitQueryTrait;

    public string $currentRoomFilter = '';
    public string $currentStatusFilter = '';

    #[On('filter-was-set')]
    public function handleFilterSet(string $tableName, string $filterKey, mixed $value): void
    {
        if ($tableName !== 'unit_table')
            return;

        // Keys are lowercase: 'room', 'status'
        if ($filterKey === 'room')
            $this->currentRoomFilter = $value ?? '';
        if ($filterKey === 'status')
            $this->currentStatusFilter = $value ?? '';
    }

    #[On('clearFilters')]
    #[On('clear-filters')]
    public function handleFilterReset(): void
    {
        $this->currentRoomFilter = '';
        $this->currentStatusFilter = '';
    }

    public function getOperationalCountProperty(): int
    {
        return $this->baseUnitsQuery()
            ->when($this->currentRoomFilter, fn($q, $v) => $q->where('room_id', $v))
            ->where('status', 'Operational')
            ->count();
    }

    public function getNonOperationalCountProperty(): int
    {
        return $this->baseUnitsQuery()
            ->when($this->currentRoomFilter, fn($q, $v) => $q->where('room_id', $v))
            ->where('status', 'Non-operational')
            ->count();
    }
    #[On('unit-deleted')]
    #[On('unit-saved')]
    #[On('unit-restored')]
    #[On('issue-reported')]
    public function handleUnitChange(): void
    {
        // No need to do anything — Livewire re-renders UnitStats
        // which recomputes getOperationalCountProperty() and getNonOperationalCountProperty()
    }
    public function render()
    {
        return view('livewire.system-units.unit-stats');
    }
}