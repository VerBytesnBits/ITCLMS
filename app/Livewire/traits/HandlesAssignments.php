<?php

namespace App\Livewire\Traits;

use Illuminate\Database\Eloquent\Model;

trait HandlesAssignments
{
    /**
     * Generic assign logic
     */
    public function assignItem(
        string $slotKey,
        int $itemId,
        string $selectedArray,   // e.g. "selectedPeripherals"
        string $assignedMap,     // e.g. "assignedPeripheralsMap"
        string $modelClass,      // e.g. Peripheral::class
        string $unitColumn = 'system_unit_id'
    ) {
        /** @var \Illuminate\Database\Eloquent\Model|null $item */
        $item = $modelClass::find($itemId);
        if (! $item) {
            return;
        }

        // detach previous assignment if any
        $previousId = $this->{$selectedArray}[$slotKey] ?? null;
        if ($previousId && $previousId !== $item->id) {
            $prev = $modelClass::find($previousId);
            if ($prev) {
                $prev->{$unitColumn} = null;
                $prev->save();
            }
        }

        // assign to this unit
        $item->{$unitColumn} = $this->unitId;
        $item->save();

        // update maps
        $this->{$selectedArray}[$slotKey] = $item->id;
        $this->{$assignedMap}[$slotKey] = $item;

        $this->dispatchBrowserEvent('toast', ['message' => "{$slotKey} assigned"]);
    }

    /**
     * Generic unassign logic
     */
    public function unassignItem(
        string $slotKey,
        string $selectedArray,
        string $assignedMap,
        string $modelClass,
        string $unitColumn = 'system_unit_id'
    ) {
        $itemId = $this->{$selectedArray}[$slotKey] ?? null;
        if (! $itemId) {
            return;
        }

        /** @var \Illuminate\Database\Eloquent\Model|null $item */
        $item = $modelClass::find($itemId);
        if ($item) {
            $item->{$unitColumn} = null;
            $item->save();
        }

        unset($this->{$selectedArray}[$slotKey]);
        unset($this->{$assignedMap}[$slotKey]);

        $this->dispatch('toast', ['message' => "{$slotKey} unassigned"]);
    }
}
