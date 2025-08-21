<?php

namespace App\Livewire\SystemUnits\Traits;

use Livewire\Attributes\On;
use App\Models\SystemUnit;

trait HandlesUnitEcho
{
    #[On('echo:units,UnitCreated')]
    public function handleUnitCreated($payload)
    {
        $unit = SystemUnit::with('room')->find($payload['unit']['id']);
        if ($unit) {
            $this->units->push($unit); // Add to collection
        }
    }

    #[On('echo:units,UnitUpdated')]
    public function handleUnitUpdated($payload)
    {
        $unit = SystemUnit::with('room')->find($payload['unit']['id']);
        if ($unit) {
            $this->units = $this->units->map(
                fn($u) => $u->id === $unit->id ? $unit : $u
            );
        }
    }

    #[On('echo:units,UnitDeleted')]
    public function handleUnitDeleted($payload)
    {
        $this->units = $this->units->reject(
            fn($u) => $u->id === $payload['id']
        )->values(); // reindex to avoid Livewire issues
    }
}
