<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\Maintenance;
use App\Models\SystemUnit;
use App\Models\Peripheral;
use App\Models\ComponentParts;
use Illuminate\Support\Facades\Auth;

class MaintenanceIndex extends Component
{
    public $maintenances;
    public $assets;

    // modal / form fields
    public bool $showModal = false;
    public string $selectedAsset = ''; // formatted: "Class|id"
    public string $type = 'repair';
    public string $description = '';

    public function mount()
    {
        $this->loadMaintenances();
        $this->loadAssets();
    }

    public function loadMaintenances()
    {
        $this->maintenances = Maintenance::with(['maintainable', 'creator', 'starter', 'completer'])
            ->latest()
            ->get();
    }

    public function loadAssets()
    {
        $assets = collect();

        // IDs of assets already reported
        $reportedAssets = Maintenance::whereIn('status', ['Pending', 'In Progress'])
            ->get(['maintainable_type', 'maintainable_id'])
            ->map(fn($m) => $m->maintainable_type . '|' . $m->maintainable_id)
            ->toArray();

        // helper for pushing assets
        $pushAsset = function ($class, $id, $label) use (&$assets, $reportedAssets) {
            $key = $class . '|' . $id;
            if (!in_array($key, $reportedAssets)) {
                $assets->push(['key' => $key, 'label' => $label]);
            }
        };

        // System Units
        SystemUnit::select('id', 'name')->orderBy('name')->get()
            ->each(fn($m) => $pushAsset(SystemUnit::class, $m->id, "Unit: {$m->name}"));

        // Peripherals
        Peripheral::select('id', 'type', 'serial_number')->orderBy('type')->get()
            ->each(fn($m) => $pushAsset(Peripheral::class, $m->id, "Peripheral: {$m->type} ({$m->serial_number})"));

        // Component Parts
        ComponentParts::select('id', 'part', 'serial_number')->orderBy('part')->get()
            ->each(fn($m) => $pushAsset(ComponentParts::class, $m->id, "Component: {$m->part} ({$m->serial_number})"));

        $this->assets = $assets;
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    protected function rules()
    {
        return [
            'selectedAsset' => 'required|string',
            'type' => 'required|in:repair,replacement,defective',
            'description' => 'required|string|max:2000',
        ];
    }

    protected function resetForm()
    {
        $this->selectedAsset = '';
        $this->type = 'repair';
        $this->description = '';
    }

    public function save()
    {
        $this->validate();

        [$class, $id] = explode('|', $this->selectedAsset);

        // Default values
        $status = 'Pending';
        $completedAt = null;
        $completedBy = null;

        // If defective or replacement, auto-complete
        if (in_array($this->type, ['defective', 'replacement'])) {
            $status = 'Completed';
            $completedAt = now();
            $completedBy = Auth::id();
        }

        $maintenance = Maintenance::create([
            'maintainable_type' => $class,
            'maintainable_id' => $id,
            'type' => $this->type,
            'description' => $this->description,
            'created_by' => Auth::id(),
            'status' => $status,
            'completed_at' => $completedAt,
            'completed_by' => $completedBy,
        ]);

        // Handle defective/replacement assets
        if (in_array($this->type, ['defective', 'replacement'])) {
            $this->markAssetDefective($maintenance->maintainable);
        }

        $this->showModal = false;
        $this->loadMaintenances();
        $this->loadAssets();

        $this->dispatch('saved');
    }

    private function markAssetDefective($asset): void
    {
        if ($asset) {
            if ($asset->getAttribute('status') !== null) {
                $asset->status = 'Defective';
            }
            if ($asset->getAttribute('condition') !== null) {
                $asset->condition = 'Poor';
            }

            // unassign logic if asset belongs to a system unit
            if (method_exists($asset, 'systemUnit')) {
                $asset->system_unit_id = null;
            }

            $asset->save();
        }
    }


    public function startMaintenance(int $id)
    {
        $m = Maintenance::findOrFail($id);

        if (!$m->started_at) {
            $m->update([
                'started_at' => now(),
                'started_by' => Auth::id(),
                'status' => 'In Progress',
            ]);
        }

        $this->loadMaintenances();
    }

    public function completeMaintenance($maintenanceId, $successful = true)
    {
        $maintenance = Maintenance::find($maintenanceId);

        if ($maintenance && $maintenance->status === 'In Progress') {
            $maintenance->status = 'Completed';
            $maintenance->completed_at = now();
            $maintenance->completed_by = auth()->id();
            $maintenance->save();

            $asset = $maintenance->maintainable;

            if ($asset) {
                if ($successful) {
                    // Mark repaired
                    $asset->status = 'Operational';
                   
                } else {
                    // Mark defective
                    $asset->status = 'Non-Operational';
                 
                    if (method_exists($asset, 'systemUnit')) {
                        $asset->system_unit_id = null; // unassign
                    }
                }
                $asset->save();
            }

            $this->loadMaintenances();
            $this->dispatch('saved');
        }
    }


    private function updateAssetStatus($asset, string $status): void
    {
        if ($asset && $asset->getAttribute('status') !== null) {
            $asset->status = $status;
            $asset->save();
        }
    }

    public function render()
    {
        return view('livewire.system-units.maintenance-index', [
            'maintenances' => $this->maintenances,
            'assets' => $this->assets,
        ]);
    }
}
