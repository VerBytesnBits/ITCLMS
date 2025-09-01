<?php

namespace App\Livewire;

use Livewire\Component;

class GlobalSearch extends Component
{
    public $query = '';
    public $results = [];

    // Define models & searchable fields
    public $models = [
        \App\Models\User::class => ['name', 'email'],
        \App\Models\SystemUnit::class => ['name', 'serial_number'],
    ];

    public function updatedQuery()
    {
        $this->results = [];

        if (strlen($this->query) < 2) {
            return; // Don’t search for <2 chars
        }

        foreach ($this->models as $model => $fields) {
            if (!class_exists($model)) {
                continue;
            }

            $query = $model::query();

            // Build OR LIKE clauses for all fields
            foreach ($fields as $field) {
                $query->orWhere($field, 'like', '%' . $this->query . '%');
            }

            $items = $query->limit(5)->get();

            if ($items->isNotEmpty()) {
                $this->results[$model] = $items;
            }
        }
    }

    public function render()
    {
        return view('livewire.global-search');
    }
}
