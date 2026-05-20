<?php

namespace App\Livewire\Issues;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\IssueReport;
use Illuminate\Support\Facades\Auth;
use Masmerise\Toaster\Toaster;

class IssueTable extends Component
{
    public $issues;

    // Modal properties
    public $resolveModal = false;
    public $selectedIssueId;
    public $resolutionNotes;
    public $resolutionAction = 'Resolved'; // default

    public function mount()
    {
        $this->refreshTable();
    }

    #[On('issue-reported')]
    public function refreshTable()
    {
        $this->issues = IssueReport::with([
            'systemUnit',
            'componentPart',
            'peripheral',
            'reporter',
            'resolver'
        ])->latest()->get();
    }

    #[On('openResolveIssue')]
    public function openResolveModal($issueId)
    {
        $this->reset(['resolutionNotes', 'resolutionAction']);
        $this->selectedIssueId = $issueId;
        $this->resolveModal = true;
    }

    public function closeResolveModal()
    {
        $this->resolveModal = false;
    }

    public function resolveIssue()
    {
        $this->validate([
            'resolutionAction' => 'required|in:Resolved,Replacement Needed,Decommissioned',
            'resolutionNotes' => 'nullable|string|max:1000',
        ]);

        $issue = IssueReport::with([
            'systemUnit',
            'componentPart',
            'peripheral',
        ])->findOrFail($this->selectedIssueId); // ✅ eager load fresh from DB

        $issue->update([
            'status' => $this->resolutionAction,
            'resolution_notes' => $this->resolutionNotes,
            'resolved_by' => Auth::id(),
        ]);

        if ($this->resolutionAction === 'Resolved') {
            if ($issue->system_unit_id && $issue->systemUnit) {
                $issue->systemUnit->update(['status' => 'Operational']); // ✅ update the unit itself
                $issue->systemUnit->components()->update(['status' => 'Operational']);
                $issue->systemUnit->peripherals()->update(['status' => 'Operational']);
            }

            if ($issue->component_part_id && $issue->componentPart) {
                $issue->componentPart->update(['status' => 'Operational']);
            }

            if ($issue->peripheral_id && $issue->peripheral) {
                $issue->peripheral->update(['status' => 'Operational']);
            }
        }

        if ($this->resolutionAction === 'Decommissioned' && $issue->system_unit_id && $issue->systemUnit) {
            $issue->systemUnit->update(['status' => 'Decommission']); // ✅ update the unit itself
            $issue->systemUnit->components()->update(['status' => 'Decommission']);
            $issue->systemUnit->peripherals()->update(['status' => 'Decommission']);
        }

        if ($this->resolutionAction === 'Replacement Needed') {
            if ($issue->component_part_id && $issue->componentPart) {
                $issue->componentPart->update(['status' => 'Defective']);
            }

            if ($issue->peripheral_id && $issue->peripheral) {
                $issue->peripheral->update(['status' => 'Defective']);
            }
        }

        $this->resolveModal = false;
        $this->refreshTable();
        Toaster::success('Issue updated successfully.');
    }
    public function render()
    {
        return view('livewire.issues.issue-table');
    }
}