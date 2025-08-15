<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SystemUnit;
use App\Support\PartsConfig;

class PartForm extends Component
{
    public ?int $unitId = null;
    public $type;
    public $fields = [];
    public $partId = null;
    public ?SystemUnit $unit = null;

    public function mount(?int $unitId, string $type, $partId = null)
    {
        // Make sure type matches config key exactly
        $this->type = $type;

        if ($unitId) {
            $this->unit = SystemUnit::findOrFail($unitId);
            $this->unitId = $unitId;
        }

        // Default fields from helper
        $this->fields = PartsConfig::defaultFields($this->type);

        // Prefill if editing
        if ($partId) {
            $modelClass = PartsConfig::modelMap()[$this->type] ?? null;
            if ($modelClass) {
                $part = $modelClass::findOrFail($partId);
                foreach ($this->fields as $field => $value) {
                    $this->fields[$field] = $part->$field ?? $value;
                }
                $this->partId = $partId;
            }
        }
    }

    public function save()
    {
        $this->validate(PartsConfig::validationRules($this->type));
        $modelClass = PartsConfig::modelMap()[$this->type] ?? null;

        if (!$modelClass) {
            throw new \Exception("Model class for type '{$this->type}' not found.");
        }

        // If editing a DB part (even in create mode)
        if ($this->partId && $modelClass::whereKey($this->partId)->exists()) {
            $modelClass::find($this->partId)->update($this->fields);
            $this->dispatch('part-saved', type: $this->type);
            return;
        }

        // If editing an existing saved unit
        if ($this->unitId) {
            $this->fields['system_unit_id'] = $this->unitId;
            $this->partId = $modelClass::create($this->fields)->id;
            $this->dispatch('part-saved', type: $this->type);
            $this->reset(['fields', 'partId']);
            $this->fields = PartsConfig::defaultFields($this->type);
            return;
        }

        // In create mode (temp parts only)
        if (!empty($this->fields['temp_id'])) {
            // Editing an existing temp part
            $this->dispatch('part-temp-updated', $this->fields);
        } else {
            // Adding a brand new temp part
            $tempFields = $this->fields;
            $tempFields['temp_id'] = uniqid('temp_');
            $tempFields['type'] = $this->type;
            $this->dispatch('part-temp-added', $tempFields);
        }
    }





    public function render()
    {
        return view('livewire.part-form', [
            'enumOptions' => PartsConfig::enumOptions()
        ]);
    }
}
