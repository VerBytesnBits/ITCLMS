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

        $modelClass = PartsConfig::modelMap()[$this->type];

        if ($this->unitId) {
            $this->fields['system_unit_id'] = $this->unitId;

            if ($this->partId) {
                $modelClass::findOrFail($this->partId)->update($this->fields);
            } else {
                $modelClass::create($this->fields);
            }

            $this->dispatch('part-saved', type: $this->type);
        } else {
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
