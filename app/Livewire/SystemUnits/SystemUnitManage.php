<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;
use Livewire\Attributes\Url;

class SystemUnitManage extends Component
{
    public $units;
    public $selectedUnit;   // Currently selected unit

    public $middleTab = 'components';  // 'components' or 'peripherals'
    public $selectedComponentType = null;  // e.g. 'processors', 'motherboards'
    public $selectedPeripheralType = null;

    public $selectedItem = null;

    // Editable fields for the selected item
    public $editFields = [];

    // Define all component and peripheral types here:
    public $componentTypes = [
        'processors',
        'cpuCoolers',
        'motherboards',
        'memories',
        'graphicsCards',
        'm2Ssds',
        'sataSsds',
        'hardDiskDrives',
        'powerSupplies',
        'computerCase',
    ];

    public $peripheralTypes = [
        'monitor',
        'keyboard',
        'mouse',
        'headset',
        'speaker',
        'webCamera',
    ];
    #[Url(as: 'id')]
    public ?int $id = null;
    public function mount($unitId = null)
    {
        $this->id = $unitId; // This will update the URL parameter `id`
        if ($unitId) {
            $this->selectedUnit = SystemUnit::findOrFail($unitId);
            $this->units = SystemUnit::where('room_id', $this->selectedUnit->room_id)->get();
        } else {
            $this->units = SystemUnit::all();
            $this->selectedUnit = $this->units->first();
        }

        $this->selectedComponentType = $this->componentTypes[0];
        $this->selectedPeripheralType = $this->peripheralTypes[0];
    }


    public function selectUnit($unitId)
    {

        $this->id = $unitId;
        $this->selectedUnit = SystemUnit::with(array_merge($this->componentTypes, $this->peripheralTypes))
            ->findOrFail($unitId);

        $this->units = SystemUnit::where('room_id', $this->selectedUnit->room_id)
            ->with(array_merge($this->componentTypes, $this->peripheralTypes))
            ->get();

        $this->selectedComponentType = $this->componentTypes[0];
        $this->selectedPeripheralType = $this->peripheralTypes[0];
        $this->selectedItem = null;
        $this->editFields = [];
    }


    public function setMiddleTab($tab)
    {
        if (in_array($tab, ['components', 'peripherals'])) {
            $this->middleTab = $tab;

            if ($tab === 'components') {
                $this->selectedComponentType = $this->componentTypes[0];
            } else {
                $this->selectedPeripheralType = $this->peripheralTypes[0];
            }
            $this->selectedItem = null;
            $this->editFields = [];
        }
    }

    public function selectMiddleType($type)
    {
        if ($this->middleTab === 'components' && in_array($type, $this->componentTypes)) {
            $this->selectedComponentType = $type;
        } elseif ($this->middleTab === 'peripherals' && in_array($type, $this->peripheralTypes)) {
            $this->selectedPeripheralType = $type;
        }
        $this->selectedItem = null;
        $this->editFields = [];
    }

    public function selectItem($item)
    {
        $this->selectedItem = $item;
        $this->editFields = $item->toArray(); // Load fields for editing
    }

    // Validation rules for editFields (extend as needed)
    protected function rules()
    {
        return [
            'editFields.brand' => 'required|string|max:255',
            'editFields.model' => 'required|string|max:255',
            // Add more rules depending on your model fields
        ];
    }

    public function saveItem()
    {
        if (!$this->selectedItem) {
            $this->addError('selectedItem', 'No item selected for update.');
            return;
        }

        $this->validate();

        $this->selectedItem->fill($this->editFields);
        $this->selectedItem->save();

        // Refresh selected unit and related units to reflect changes
        $this->selectUnit($this->selectedUnit->id);

        session()->flash('message', 'Item updated successfully.');
    }

    public function render()
    {
        return view('livewire.system-units.system-unit-manage', [
            'units' => $this->units,
            'selectedUnit' => $this->selectedUnit,
            'middleTab' => $this->middleTab,
            'componentTypes' => $this->componentTypes,
            'peripheralTypes' => $this->peripheralTypes,
            'selectedComponentType' => $this->selectedComponentType,
            'selectedPeripheralType' => $this->selectedPeripheralType,
            'selectedItem' => $this->selectedItem,
        ]);
    }
}

