<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\{
    SystemUnit,
    Room,
    Processor,
    CpuCooler,
    Motherboard,
    Memory,
    GraphicsCard,
    M2Ssd,
    SataSsd,
    HardDiskDrive,
    PowerSupply,
    ComputerCase,
    Display,
    Headset,
    Keyboard,
    Mouse,
    Speaker,
    WebDigitalCamera,
};
use App\Events\UnitUpdated;

class UnitForm extends Component
{
    public ?int $unitId = null;

    public $name;
    public $status = 'Operational';
    public $room_id;

    public string $middleTab = 'components';
    public $selectedComponentType = null;
    public $selectedPeripheralType = null;
    public $formMode = false;
    public $editingPartId = null;

    public array $componentTypes = [
        'processor',
        'cpuCooler',
        'motherboard',
        'memories',
        'graphicsCards',
        'powerSupply',
        'computerCase',
        'm2Ssds',
        'sataSsds',
        'hardDiskDrives'
    ];

    public array $peripheralTypes = [
        'monitor',
        'keyboard',
        'mouse',
        'headset',
        'speaker',
        'webCamera'
    ];

    public array $availableComponents = [];
    public array $availablePeripherals = [];

    public array $unitSelections = [
        'components' => [],
        'peripherals' => []
    ];

    public $rooms = [];
    public $modalMode = 'create';

    // Inline Add/Edit Part
    public $newPart = ['brand' => '', 'model' => ''];
    public $editPartId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'status' => 'required|in:Operational,Needs Repair,Non-Operational',
        'room_id' => 'required|exists:rooms,id'
    ];

    public function mount($unitId = null)
    {
        $this->rooms = Room::all();

        if ($unitId) {
            $unit = SystemUnit::with(array_merge($this->componentTypes, $this->peripheralTypes))
                ->findOrFail($unitId);

            $this->fill([
                'name' => $unit->name,
                'status' => $unit->status,
                'room_id' => $unit->room_id,
                'unitId' => $unitId
            ]);

            foreach ($this->componentTypes as $type) {
                if ($relation = $unit->{\Str::camel($type)}) {
                    $this->unitSelections['components'][$type] = collect(is_iterable($relation) ? $relation : [$relation])
                        ->map(fn($item) => array_merge($item->only(['id', 'brand', 'model']), [
                            'temp_id' => uniqid('temp_')
                        ]))
                        ->toArray();
                }
            }

            foreach ($this->peripheralTypes as $type) {
                if ($relation = $unit->{\Str::camel($type)}) {
                    $this->unitSelections['peripherals'][$type] = collect(is_iterable($relation) ? $relation : [$relation])
                        ->map(fn($item) => array_merge($item->only(['id', 'brand', 'model']), [
                            'temp_id' => uniqid('temp_')
                        ]))
                        ->toArray();
                }
            }

            $this->modalMode = 'edit';
        }

        $this->loadAvailableItems();
    }


    /**
     * Generate unit name when a room is selected.
     */
    public function regenerateName($value)
    {
        if ($this->modalMode !== 'create' || empty($value)) {
            return;
        }

        $room = Room::find($value);
        if (!$room)
            return;

        // Extract number from room name or fallback to ID
        preg_match('/\d+/', $room->name, $matches);
        $labNumber = !empty($matches[0]) ? $matches[0] : $room->id;

        // Find the highest unit number in that room
        $lastNumber = SystemUnit::where('room_id', $room->id)
            ->selectRaw('MAX(CAST(SUBSTRING_INDEX(name, "-", -1) AS UNSIGNED)) as max_num')
            ->value('max_num');

        $nextNumber = str_pad(($lastNumber ?? 0) + 1, 2, '0', STR_PAD_LEFT);

        $this->name = "PC-L{$labNumber}-{$nextNumber}";
    }

    protected function loadAvailableItems()
    {
        // Components
        $this->availableComponents = [];
        foreach ($this->componentTypes as $type) {
            $model = $this->modelMap()[$type];

            $this->availableComponents[$type] = $model::where(function ($query) use ($type) {
                $query->whereNull('system_unit_id'); // unassigned items
                if ($this->unitId) {
                    $query->orWhere('system_unit_id', $this->unitId); // include items already assigned to this unit
                }
            })->get()->toArray();
        }

        // Peripherals
        $this->availablePeripherals = [];
        foreach ($this->peripheralTypes as $type) {
            $model = $this->modelMap()[$type];

            $this->availablePeripherals[$type] = $model::where(function ($query) use ($type) {
                $query->whereNull('system_unit_id'); // unassigned items
                if ($this->unitId) {
                    $query->orWhere('system_unit_id', $this->unitId); // include items already assigned to this unit
                }
            })->get()->toArray();
        }
    }





    public function setMiddleTab($tab)
    {
        $this->middleTab = $tab;
    }

    public function selectMiddleType($type)
    {
        if ($this->middleTab === 'components') {
            $this->selectedComponentType = $type;
        } else {
            $this->selectedPeripheralType = $type;
        }
    }

    public function addToUnit($type, $idOrTempId)
    {
        $targetGroup = $this->middleTab;
        $list = $targetGroup === 'components' ? $this->availableComponents : $this->availablePeripherals;

        $item = collect($list[$type] ?? [])->firstWhere('id', $idOrTempId)
            ?? collect($this->unitSelections[$targetGroup][$type] ?? [])
                ->firstWhere('temp_id', $idOrTempId);

        if ($item) {
            // Assign temp_id if missing
            if (!isset($item['id']) && !isset($item['temp_id'])) {
                $item['temp_id'] = uniqid('temp_');
            }

            $alreadyAdded = collect($this->unitSelections[$targetGroup][$type] ?? [])
                ->contains(fn($existing) => ($existing['id'] ?? $existing['temp_id']) === ($item['id'] ?? $item['temp_id']));

            if (!$alreadyAdded) {
                $this->unitSelections[$targetGroup][$type][] = $item;
            }
        }

        $this->dispatch('unit-parts-updated', [
            'unitId' => $this->unitId ?? 'temp_' . spl_object_id($this),
            'selections' => $this->unitSelections
        ]);
    }



    public function removeFromUnit($type, $idOrTempId)
    {
        $isComponent = array_key_exists($type, $this->unitSelections['components']);
        $targetGroup = $isComponent ? 'components' : 'peripherals';

        $this->unitSelections[$targetGroup][$type] = array_values(array_filter(
            $this->unitSelections[$targetGroup][$type],
            fn($item) => ($item['id'] ?? $item['temp_id']) != $idOrTempId
        ));

        $this->dispatch('unit-parts-updated', [
            'unitId' => $this->unitId ?? 'temp_' . spl_object_id($this),
            'selections' => $this->unitSelections
        ]);
    }






    public function addTempPart($type, $fields)
    {
        $this->unitSelections[
            $this->middleTab
        ][$type][] = $fields;
    }
    protected $listeners = [
        'part-saved' => 'handlePartSaved',
        'part-temp-added' => 'handlePartTempAdded',
    ];

    public function handlePartTempAdded($payload)
    {
        $type = $payload['type'];
        unset($payload['type']);

        $targetGroup = in_array($type, $this->componentTypes) ? 'components' : 'peripherals';

        if (!isset($this->unitSelections[$targetGroup][$type])) {
            $this->unitSelections[$targetGroup][$type] = [];
        }

        // Assign temp_id if missing
        if (!isset($payload['temp_id'])) {
            $payload['temp_id'] = uniqid('temp_');
        }

        // Prevent duplicates
        $alreadyExists = collect($this->unitSelections[$targetGroup][$type])
            ->contains(fn($item) => ($item['id'] ?? $item['temp_id']) === ($payload['id'] ?? $payload['temp_id']));

        if (!$alreadyExists) {
            $this->unitSelections[$targetGroup][$type][] = $payload;
        }

        $this->formMode = false;
        $this->editingPartId = null;
    }




    public function save()
    {
        $this->validate();

        // If editing existing unit
        if ($this->unitId) {
            $unit = SystemUnit::findOrFail($this->unitId);
        } else {
            // New unit
            $unit = SystemUnit::create([
                'name' => $this->name,
                'status' => $this->status,
                'room_id' => $this->room_id,
            ]);
            $this->unitId = $unit->id;
        }

        // --------------------
        // Components
        // --------------------
        foreach ($this->unitSelections['components'] as $type => &$items) {
            $modelClass = $this->modelMap()[$type];
            $isMany = in_array($type, ['memories', 'graphicsCards', 'm2Ssds', 'sataSsds', 'hardDiskDrives']);
            $ids = [];

            foreach ($items as &$item) {
                if (!empty($item['id'])) {
                    // Existing DB item → reassign
                    $model = $modelClass::find($item['id']);
                    if ($model) {
                        $model->system_unit_id = $unit->id;
                        $model->save();
                        $ids[] = $model->id;
                    }
                } else {
                    // New temporary item → create
                    $item['system_unit_id'] = $unit->id;
                    $newModel = $modelClass::create($item);
                    $item['id'] = $newModel->id;
                    unset($item['temp_id']);
                    $ids[] = $newModel->id;
                }
            }

            // Detach previous items
            if ($isMany) {
                $modelClass::where('system_unit_id', $unit->id)
                    ->whereNotIn('id', $ids)
                    ->update(['system_unit_id' => null]);
            } else {
                $modelClass::where('system_unit_id', $unit->id)
                    ->where('id', '!=', $ids[0] ?? 0)
                    ->update(['system_unit_id' => null]);
            }

            unset($item);
        }
        unset($items);

        // --------------------
        // Peripherals
        // --------------------
        foreach ($this->unitSelections['peripherals'] as $type => &$items) {
            $modelClass = $this->modelMap()[$type];
            $ids = [];

            foreach ($items as &$item) {
                if (!empty($item['id'])) {
                    $model = $modelClass::find($item['id']);
                    if ($model) {
                        $model->system_unit_id = $unit->id;
                        $model->save();
                        $ids[] = $model->id;
                    }
                } else {
                    $item['system_unit_id'] = $unit->id;
                    $newModel = $modelClass::create($item);
                    $item['id'] = $newModel->id;
                    unset($item['temp_id']);
                    $ids[] = $newModel->id;
                }
            }

            // Detach peripherals no longer selected
            $modelClass::where('system_unit_id', $unit->id)
                ->whereNotIn('id', $ids)
                ->update(['system_unit_id' => null]);

            unset($item);
        }
        unset($items);

        // --------------------
        // Flash & reset
        // --------------------
        session()->flash('success', 'System unit saved!');
        event(new UnitUpdated($unit));

        $this->resetExcept('rooms', 'componentTypes', 'peripheralTypes');
        $this->loadAvailableItems();
    }



    private function modelMap()
    {
        return [
            'processor' => Processor::class,
            'cpuCooler' => CpuCooler::class,
            'motherboard' => Motherboard::class,
            'memories' => Memory::class,
            'graphicsCards' => GraphicsCard::class,
            'powerSupply' => PowerSupply::class,
            'computerCase' => ComputerCase::class,
            'm2Ssds' => M2Ssd::class,
            'sataSsds' => SataSsd::class,
            'hardDiskDrives' => HardDiskDrive::class,
            'monitor' => Display::class,
            'keyboard' => Keyboard::class,
            'mouse' => Mouse::class,
            'headset' => Headset::class,
            'speaker' => Speaker::class,
            'webCamera' => WebDigitalCamera::class,
        ];
    }


    public function render()
    {
        return view('livewire.system-units.unit-form', [
            'rooms' => $this->rooms,
            'componentTypes' => $this->componentTypes,
            'peripheralTypes' => $this->peripheralTypes,
            'availableComponents' => $this->availableComponents,
            'availablePeripherals' => $this->availablePeripherals,
        ]);
    }
}
