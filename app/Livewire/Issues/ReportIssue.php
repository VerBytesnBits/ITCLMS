<?php

namespace App\Livewire\Issues;

use Livewire\Component;
use App\Models\IssueReport;
use App\Models\SystemUnit;
use Illuminate\Support\Facades\Auth;

class ReportIssue extends Component
{
    public $showModal = false;
    public $systemUnitId;
    public $issueCategory = 'general'; // general/component/peripheral
    public $selectedItemId;
    public $issueType;
    public $customIssueType;
    public $remarks;

    public $components = [];
    public $peripherals = [];

    protected $listeners = ['openReportIssue' => 'open'];

    public function open($systemUnitId = null)
    {
        $this->reset(['issueCategory', 'selectedItemId', 'issueType', 'customIssueType', 'remarks']);
        $this->systemUnitId = $systemUnitId;

        if ($systemUnitId) {
            $unit = SystemUnit::with(['components', 'peripherals'])->find($systemUnitId);

            // Only show components and peripherals that are operational
            $this->components = $unit?->components?->where('status', 'In Use')->values() ?? [];
            $this->peripherals = $unit?->peripherals?->where('status', 'In Use')->values() ?? [];
        }

        $this->showModal = true;
    }


    public function close()
    {

        $this->showModal = false;
    }

    public function submit()
    {
        $this->validate([
            'issueCategory' => 'required|in:general,component,peripheral',
            'issueType' => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        $issue = IssueReport::create([
            'system_unit_id' => $this->systemUnitId,
            'component_part_id' => $this->issueCategory === 'component' ? $this->selectedItemId : null,
            'peripheral_id' => $this->issueCategory === 'peripheral' ? $this->selectedItemId : null,
            'issue_type' => $this->issueType === 'Other' ? $this->customIssueType : $this->issueType,
            'remarks' => $this->remarks,
            'reported_by' => Auth::id(),
            'status' => 'In Progress',
        ]);

        //  Update system unit status to Non-Operational if there’s a valid linked unit
        if ($this->systemUnitId) {
            $unit = SystemUnit::find($this->systemUnitId);
            if ($unit) {
                $unit->update(['status' => 'Non-Operational']);
            }
        }

        $this->dispatch('issue-reported');
        $this->close();
        session()->flash('success', 'Issue reported successfully, system unit marked as Non-Operational.');
    }

    public function render()
    {
        return view('livewire.issues.report-issue');
    }
}
