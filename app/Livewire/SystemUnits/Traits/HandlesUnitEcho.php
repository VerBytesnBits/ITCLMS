<?php

namespace App\Livewire\SystemUnits\Traits;

use Livewire\Attributes\On;
use App\Models\SystemUnit;
use App\Support\PartsConfig;

trait HandlesUnitEcho
{
    #[On('echo:units,UnitCreated')]
    public function handleUnitCreated($payload)
    {
        $unitData = (object) $payload['unit'];

        // Reconstruct dynamic parts
        foreach (['components', 'peripherals'] as $group) {
            $types = $group === 'components'
                ? PartsConfig::componentTypes()
                : PartsConfig::peripheralTypes();

            foreach ($types as $type) {
                $unitData->$type = []; // empty arrays for real-time rendering
            }
        }

        // Append to units collection
        $this->units->push($unitData);
    }

    #[On('echo:units,UnitUpdated')]
    public function handleUnitUpdated($payload)
    {
        $unitData = (object) $payload['unit'];

        // Find the existing unit in collection
        $existing = $this->units->firstWhere('id', $unitData->id);
        if ($existing) {
            // Update properties in-place
            $existing->name = $unitData->name;
            $existing->status = $unitData->status;
            $existing->room_id = $unitData->room_id;
        }
    }


    #[On('echo:units,UnitDeleted')]
    public function handleUnitDeleted($payload)
    {
        $this->units = $this->units->reject(fn($u) => $u->id === $payload['id'])->values();
    
    }
}
