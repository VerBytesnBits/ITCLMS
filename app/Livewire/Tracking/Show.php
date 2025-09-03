<?php

namespace App\Livewire\Tracking;

use Livewire\Component;
use App\Models\SystemUnit;
use App\Models\ComponentParts;
use App\Models\Peripheral;

class Show extends Component
{
    public string $type;
    public string $serial;
    public $item;

    public function mount(string $type, string $serial)
    {
        $this->type = strtolower($type);
        $this->serial = $serial;

        $model = match($this->type) {
            'unit' => SystemUnit::class,
            'component' => ComponentParts::class,
            'peripheral' => Peripheral::class,
            default => abort(404, 'Invalid type'),
        };

        $this->item = $model::where('serial_number', $serial)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.tracking.show');
    }
}
