<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\Maintenance;
use App\Models\SystemUnit;
use Illuminate\Support\Facades\Auth;

class MaintenanceIndex extends Component
{
    public $maintenances;
    public $systemUnits;

    public $selectedUnit = null;
    public $type;
    public $description;
    public $status = 'Pending';

    public $showModal = false;

    protected $listeners = ['refreshMaintenances' => 'loadMaintenances'];

    public function mount()
    {
        $this->systemUnits = SystemUnit::all();
        $this->loadMaintenances();
    }

    public function loadMaintenances()
    {
        $this->maintenances = Maintenance::with('unit', 'user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function openModal($unitId = null)
    {
        $this->selectedUnit = $unitId;
        $this->type = null;
        $this->description = null;
        $this->status = 'Pending';
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'selectedUnit' => 'required|exists:system_units,id',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:Pending,In Progress,Completed',
        ]);

        Maintenance::create([
            'system_unit_id' => $this->selectedUnit,
            'user_id' => Auth::id(),
            'type' => $this->type,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        // If maintenance is repair or replacement, update unit status to Under Maintenance
        if (in_array(strtolower($this->type), ['repair', 'replacement'])) {
            $unit = SystemUnit::find($this->selectedUnit);
            $unit->status = 'Under Maintenance';
            $unit->condition = 'Non-operational';
            $unit->save();
        }

        $this->showModal = false;
        $this->loadMaintenances();

        $this->dispatch('swal', [
            'toast' => true,
            'icon' => 'success',
            'title' => 'Maintenance record added!',
            'timer' => 3000
        ]);
    }

    // **Start Maintenance**
    public function startMaintenance($maintenanceId)
    {
        $maintenance = Maintenance::find($maintenanceId);
        if ($maintenance && $maintenance->status === 'Pending') {
            $maintenance->status = 'In Progress';
            $maintenance->save();

            // Update unit status
            $unit = $maintenance->unit;
            $unit->status = 'Under Maintenance';
            $unit->condition = 'Non-operational';
            $unit->save();

            $this->loadMaintenances();
            $this->dispatch('swal', [
                'toast' => true,
                'icon' => 'info',
                'title' => 'Maintenance started!',
                'timer' => 3000
            ]);
        }
    }

    // **Complete Maintenance**
    public function completeMaintenance($maintenanceId)
    {
        $maintenance = Maintenance::find($maintenanceId);
        if ($maintenance && $maintenance->status === 'In Progress') {
            $maintenance->status = 'Completed';
            $maintenance->save();

            // Update unit status back to Operational if repaired
            $unit = $maintenance->unit;
            $unit->status = 'Available';
            $unit->condition = 'Operational';
            $unit->save();

            $this->loadMaintenances();
            $this->dispatch('swal', [
                'toast' => true,
                'icon' => 'success',
                'title' => 'Maintenance completed!',
                'timer' => 3000
            ]);
        }
    }

    public function render()
    {
        return view('livewire.system-units.maintenance-index');
    }
}
