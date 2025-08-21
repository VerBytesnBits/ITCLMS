<?php

namespace App\Livewire\UnitReports;

use Livewire\Component;
use App\Support\PartsConfig;
 use Illuminate\Support\Facades\Config;
use App\Models\SystemUnit;
use App\Models\UnitReport;

class ReportForm extends Component
{
    public $unitId;
    public $parts = []; // Parts for this unit
    public $partId;
    public $issue;
    public $description;
    public ?SystemUnit $unit = null;

    // UI-only options (can stay here, since they’re presentation-specific)
    public $issueOptions = [
        'General' => ['Slow performance', 'Not turning on', 'Overheating', 'Other'],
        'processor' => ['Overheating', 'No POST', 'Slow processing', 'Other'],
        'memories' => ['Not detected', 'Blue screen', 'Frequent crashes', 'Other'],
        'graphicsCards' => ['Artifacts', 'No display', 'Driver failure', 'Other'],
        'keyboard' => ['Keys not working', 'Connection issues', 'Other'],
        'mouse' => ['Not detected', 'Pointer skipping', 'Other'],
        // extend as needed…
    ];





    public function mount(?int $unitId = null)
    {
        if ($unitId) {
            $this->unit = SystemUnit::with(PartsConfig::unitRelations())
                ->find($unitId); // use find, not findOrFail
        }

        if ($this->unit) {
            $parts = PartsConfig::getPartsForUnit($this->unit) ?? [];
            array_unshift($parts, [
                'id' => null,
                'type' => 'General',
                'label' => '-- General Issue --',
            ]);
            $this->parts = $parts;
        } else {
            $this->parts = [];
        }
    }



   

    public function submit()
    {
        $this->validate([
            'issue' => 'required|string',
            'description' => $this->issue === 'Other' ? 'required|string' : 'nullable|string',
        ]);

        $partId = null;
        $partType = null;

        if ($this->partId !== null && $this->partId !== '') {
            $selected = collect($this->parts)->firstWhere('id', (int) $this->partId);

            if ($selected) {
                $partId = (int) $selected['id'];

                // Use PartsConfig modelMap to resolve morph type
                $modelMap = PartsConfig::modelMap();
                $partType = $modelMap[$selected['type']] ?? null;
            }
        }

        // 1. Create the report
        $report = UnitReport::create([
            'system_unit_id' => $this->unitId,
            'part_type' => $partType,
            'part_id' => $partId,
            'reported_by' => auth()->id(),
            'issue' => $this->issue,
            'description' => $this->description,
        ]);

        // 2. Update unit status based on issue mapping
        $mapping = Config::get('report.status_map', []);
        $newStatus = $mapping[$this->issue] ?? 'Needs Repair';

        if ($this->unit) {
            $this->unit->update(['status' => $newStatus]);
        }

        // 3. Fire event + reset form
        $this->dispatch('report-submitted', $report);

        $this->reset(['partId', 'issue', 'description']);
    }

    public function render()
    {
        return view('livewire.unit-reports.report-form', [
            'partsConfig' => PartsConfig::get(), // config for dropdowns/labels
            'parts' => $this->parts,      // actual parts for this unit
        ]);
    }
}
