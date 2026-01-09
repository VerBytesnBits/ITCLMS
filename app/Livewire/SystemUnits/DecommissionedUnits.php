<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;

class DecommissionedUnits extends Component
{
    public bool $show = false;

   
    public array $units = [];
    public array $expandedUnits = [];
    public array $selectedComponents = [];
    public array $selectedPeripherals = [];

   
    public function toggle()
    {
        $this->show = !$this->show;
        if ($this->show) {
            $this->loadUnits();
        }
    }

    public function loadUnits()
    {
        $user = auth()->user();

        $query = SystemUnit::onlyTrashed()->with([
            'components' => fn($q) => $q->onlyTrashed(),
            'peripherals' => fn($q) => $q->onlyTrashed()
        ]);

        if (!$user->hasRole('chairman')) {
            $roomIds = $user->rooms->pluck('id');
            $query->whereIn('room_id', $roomIds);
        }

        $this->units = $query->orderBy('deleted_at', 'desc')->get()->map(function ($unit) {
            return [
                'id' => $unit->id,
                'name' => $unit->name,
                'deleted_at' => $unit->deleted_at?->format('Y-m-d H:i'),
                'components' => $unit->components->toArray() ?? [],
                'peripherals' => $unit->peripherals->toArray() ?? [],
            ];
        })->toArray();

        $this->dispatch("unit-restored");
    }

    
    public function toggleUnitExpansion($unitId)
    {
        
        $this->selectedComponents[$unitId] = $this->selectedComponents[$unitId] ?? [];
        $this->selectedPeripherals[$unitId] = $this->selectedPeripherals[$unitId] ?? [];

        if (in_array($unitId, $this->expandedUnits)) {
            $this->expandedUnits = array_diff($this->expandedUnits, [$unitId]);
        } else {
            $this->expandedUnits[] = $unitId;
        }
    }

    public function restoreUnitOrChildren($unitId)
    {
        $unit = SystemUnit::withTrashed()->find($unitId);
        if (!$unit)
            return;

        $components = $this->selectedComponents[$unitId] ?? [];
        $peripherals = $this->selectedPeripherals[$unitId] ?? [];

        if (!empty($components) || !empty($peripherals)) {
            
            if (!empty($components)) {
                $unit->components()
                    ->onlyTrashed()
                    ->whereIn('id', $components)
                    ->update(['status' => 'Available']); 
                $unit->components()
                    ->onlyTrashed()
                    ->whereIn('id', $components)
                    ->restore();
            }

            if (!empty($peripherals)) {
                $unit->peripherals()
                    ->onlyTrashed()
                    ->whereIn('id', $peripherals)
                    ->update(['status' => 'Available']);
                $unit->peripherals()
                    ->onlyTrashed()
                    ->whereIn('id', $peripherals)
                    ->restore();
            }

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Selected components/peripherals restored successfully.'
            ]);
        } else {
            
            $unit->restore();

           
            $unit->components()->onlyTrashed()->restore();
            $unit->peripherals()->onlyTrashed()->restore();

           
            $unit->components()->update(['status' => 'In Use']);
            $unit->peripherals()->update(['status' => 'In Use']);


            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Unit and all its components/peripherals restored successfully.'
            ]);
        }

        
        $this->loadUnits();

       
        $this->selectedComponents[$unitId] = [];
        $this->selectedPeripherals[$unitId] = [];
    }




    public function render()
    {
        return view('livewire.system-units.decommissioned-units');
    }
}
