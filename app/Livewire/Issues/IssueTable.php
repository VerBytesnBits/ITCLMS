<?php

namespace App\Livewire\Issues;

use Livewire\Component;
use App\Models\IssueReport;
use Illuminate\Support\Facades\Auth;

class IssueTable extends Component
{
    public $issues;

    // Modal properties
    public $resolveModal = false;
    public $selectedIssueId;
    public $resolutionNotes;
    public $resolutionAction = 'Resolved'; // default

    protected $listeners = [
        'issue-reported' => 'refreshTable',
        'openResolveIssue' => 'openResolveModal',
    ];

    public function mount()
    {
        $this->refreshTable();
    }

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

        $issue = IssueReport::findOrFail($this->selectedIssueId);

        // Update the issue itself
        $issue->update([
            'status' => $this->resolutionAction,
            'resolution_notes' => $this->resolutionNotes,
            'resolved_by' => Auth::id(),
        ]);

        // Handle linked items
        if ($this->resolutionAction === 'Decommissioned' && $issue->system_unit_id) {
            $unit = $issue->systemUnit;

            if ($unit) {
                // Update all components
                $unit->components()->update(['status' => 'Decommission']);

                // Update all peripherals
                $unit->peripherals()->update(['status' => 'Decommission']);
            }
        }

        if ($this->resolutionAction === 'Replacement Needed') {
            // Only update the reported item
            if ($issue->component_part_id) {
                $issue->componentPart->update(['status' => 'Defective']);
            }

            if ($issue->peripheral_id) {
                $issue->peripheral->update(['status' => 'Defective']);
            }
        }

        $this->resolveModal = false;
        $this->refreshTable();
        session()->flash('success', 'Issue updated successfully.');
    }


    public function render()
    {
        return view('livewire.issues.issue-table');
    }
}
