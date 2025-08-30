<?php

namespace App\Livewire\Processors;

use Livewire\Component;
use App\Models\Processor;
use App\Models\SystemUnit;

class Create extends Component
{
    public SystemUnit $unit;

    public $brand;
    public $model;
    public $generation;
    public $cores;
    public $threads;
    public $base_clock;
    public $boost_clock;
    public $serial_number;
    public $status;
    public $date_purchased;

    protected $rules = [
        'brand' => 'required|string|max:255',
        'model' => 'required|string|max:255',
        'generation' => 'nullable|string|max:255',
        'cores' => 'nullable|integer|min:1',
        'threads' => 'nullable|integer|min:1',
        'base_clock' => 'nullable|string|max:255',
        'boost_clock' => 'nullable|string|max:255',
        'serial_number' => 'nullable|string|max:255',
        'status' => 'required|in:Working,Under Maintenance,Decommissioned',
        'date_purchased' => 'nullable|date',
    ];

    public function mount(SystemUnit $unit)
    {
        $this->unit = $unit;
    }

    public function save()
    {
        $this->validate();

        Processor::create([
            'system_unit_id' => $this->unit->id,
            'brand' => $this->brand,
            'model' => $this->model,
            'generation' => $this->generation,
            'cores' => $this->cores,
            'threads' => $this->threads,
            'base_clock' => $this->base_clock,
            'boost_clock' => $this->boost_clock,
            'serial_number' => $this->serial_number,
            'status' => $this->status,
            'date_purchased' => $this->date_purchased,
        ]);

        session()->flash('success', 'Processor added successfully.');

        // Redirect to the system units index or any page you want
        return redirect()->route('system-units.index', $this->unit->id);
    }

    public function render()
    {
        return view('livewire.processors.create');
    }
}
