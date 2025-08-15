<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\{
    SystemUnit,
    Room
};
use App\Events\UnitUpdated;
use App\Support\PartsConfig;
use Livewire\Attributes\On;

class UnitForm extends Component
{
    // ==============================
    // Properties
    // ==============================
    public ?int $unitId = null;
    public string $name = '';
    public string $status = 'Operational';
    public ?int $room_id = null;

    // UI state management
    public string $middleTab = 'components';
    public ?string $selectedComponentType = null;
    public ?string $selectedPeripheralType = null;
    public bool $formMode = false;
    public ?int $editingPartId = null;

    // Available part types (fetched from PartsConfig)
    public array $componentTypes = [];
    public array $peripheralTypes = [];

    // Lists for available and selected items
    public array $availableComponents = [];
    public array $availablePeripherals = [];
    public array $unitSelections = [
        'components' => [],
        'peripherals' => []
    ];

    // Misc UI data
    public $rooms = [];
    public string $modalMode = 'create';
    public array $newPart = ['brand' => '', 'model' => ''];
    public ?int $editPartId = null;

    // ==============================
    // Validation
    // ==============================
    protected $rules = [
        'name' => 'required|string|max:255',
        'status' => 'required|in:Operational,Needs Repair,Non-Operational',
        'room_id' => 'required|exists:rooms,id'
    ];


    // ==============================
    // Lifecycle
    // ==============================
    public function mount($unitId = null)
    {
        // Fetch available rooms
        $this->rooms = Room::all();

        // Load component & peripheral type keys
        $this->componentTypes = PartsConfig::componentTypes();
        $this->peripheralTypes = PartsConfig::peripheralTypes();

        // Initialize selections to empty arrays
        $this->initializeSelections();

        // If editing existing unit
        if ($unitId) {
            $this->loadUnitForEdit($unitId);
        }

        // Load available parts lists
        $this->loadAvailableItems();
    }

    // ==============================
    // Load Unit for Edit
    // ==============================
    private function loadUnitForEdit($unitId)
    {
        $unit = SystemUnit::with(array_merge($this->componentTypes, $this->peripheralTypes))
            ->findOrFail($unitId);

        $this->fill([
            'name' => $unit->name,
            'status' => $unit->status,
            'room_id' => $unit->room_id,
            'unitId' => $unitId
        ]);

        $this->loadUnitSelections($unit);
        $this->modalMode = 'edit';
    }

    private function loadUnitSelections(SystemUnit $unit)
    {
        $this->initializeSelections();

        foreach (['components' => $this->componentTypes, 'peripherals' => $this->peripheralTypes] as $group => $types) {
            foreach ($types as $type) {
                if ($relation = $unit->{\Str::camel($type)}) {
                    $items = collect(is_iterable($relation) ? $relation : [$relation])
                        ->map(fn($item) => array_merge(
                            $item->only(['id', 'brand', 'model']),
                            ['temp_id' => uniqid('temp_')]
                        ))
                        ->toArray();

                    $this->unitSelections[$group][$type] = $items;
                }
            }
        }
    }

    private function initializeSelections()
    {
        foreach (['components' => $this->componentTypes, 'peripherals' => $this->peripheralTypes] as $group => $types) {
            foreach ($types as $type) {
                $this->unitSelections[$group][$type] = $this->unitSelections[$group][$type] ?? [];
            }
        }
    }

    // ==============================
    // Generate Name for New Units
    // ==============================
    public function regenerateName($roomId)
    {
        if ($this->modalMode !== 'create' || empty($roomId))
            return;

        $room = Room::find($roomId);
        if (!$room)
            return;

        preg_match('/\d+/', $room->name, $matches);
        $labNumber = $matches[0] ?? $room->id;

        $lastNumber = SystemUnit::where('room_id', $room->id)
            ->selectRaw('MAX(CAST(SUBSTRING_INDEX(name, "-", -1) AS UNSIGNED)) as max_num')
            ->value('max_num');

        $nextNumber = str_pad(($lastNumber ?? 0) + 1, 2, '0', STR_PAD_LEFT);
        $this->name = "PC-L{$labNumber}-{$nextNumber}";
    }

    // ==============================
    // Load Available Items
    // ==============================
    protected function loadAvailableItems()
    {
        $this->availableComponents = PartsConfig::getAvailableParts(
            $this->componentTypes,
            $this->unitId,
            $this->unitSelections['components']
        );

        $this->availablePeripherals = PartsConfig::getAvailableParts(
            $this->peripheralTypes,
            $this->unitId,
            $this->unitSelections['peripherals']
        );
    }

    // ==============================
    // Tab & Selection Methods
    // ==============================
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

    // ==============================
    // Add Part to Unit
    // ==============================
    public function addToUnit($type, $idOrTempId)
    {
        $group = $this->middleTab;
        $list = $group === 'components' ? $this->availableComponents : $this->availablePeripherals;

        $item = collect($list[$type] ?? [])->firstWhere('id', $idOrTempId)
            ?? collect($this->unitSelections[$group][$type] ?? [])->firstWhere('temp_id', $idOrTempId);

        if (!$item)
            return;

        $modelClass = PartsConfig::modelMap()[$type];

        // If part is not saved in DB yet
        if (empty($item['id'])) {
            $partData = [
                'brand' => $item['brand'] ?? '',
                'model' => $item['model'] ?? '',
                'system_unit_id' => $this->unitId ?? null,
            ];
            $newModel = $modelClass::create($partData);
            $item['id'] = $newModel->id;
            unset($item['temp_id']);
        } else {
            // If already exists in DB, link it to the unit
            if ($this->unitId) {
                $model = $modelClass::find($item['id']);
                if ($model) {
                    $model->system_unit_id = $this->unitId;
                    $model->save();
                }
            }
        }

        // Avoid duplicates in selection
        $alreadyAdded = collect($this->unitSelections[$group][$type] ?? [])
            ->contains(fn($existing) => ($existing['id'] ?? null) === ($item['id'] ?? null));

        if (!$alreadyAdded) {
            $this->unitSelections[$group][$type][] = $item;
        }

        $this->loadAvailableItems();
        $this->dispatch('unit-parts-updated', [
            'unitId' => $this->unitId,
            'selections' => $this->unitSelections
        ]);
    }

    // ==============================
    // Remove Part from In-memory Selection
    // ==============================
    public function removeFromUnit($type, $idOrTempId)
    {
        $group = array_key_exists($type, $this->unitSelections['components']) ? 'components' : 'peripherals';

        // If this unit is saved in DB, unassign the part
        if ($this->unitId && is_numeric($idOrTempId)) {
            $modelClass = PartsConfig::modelMap()[$type];
            $modelClass::where('id', $idOrTempId)
                ->where('system_unit_id', $this->unitId)
                ->update(['system_unit_id' => null]);
        }

        // Remove from in-memory selection
        $this->unitSelections[$group][$type] = array_values(array_filter(
            $this->unitSelections[$group][$type] ?? [],
            fn($item) => ($item['id'] ?? $item['temp_id']) != $idOrTempId
        ));

        // Refresh available items
        $this->loadAvailableItems();

        $this->dispatch('unit-parts-updated', [
            'unitId' => $this->unitId ?? 'temp_' . spl_object_id($this),
            'selections' => $this->unitSelections
        ]);
    }


    // ==============================
    // Delete Part from DB (Completely)
    // ==============================
    public function deleteUnitPart($type, $partId)
    {
        $modelClass = PartsConfig::modelMap()[$type];
        $part = $modelClass::find($partId);

        if ($part) {
            // Permanently delete from DB
            $part->delete();
        }

        // Also remove from local array so UI updates immediately
        $this->removeFromUnit($type, $partId);

        // Refresh available items list
        $this->loadAvailableItems();

        session()->flash('success', 'Part deleted successfully.');
    }

    // // ==============================
    // // Edit Part to Unit
    // // ==============================
    public function editUnitPart($type, $partId)
    {
        $this->selectedType = $type;
        $this->editingPartId = $partId;
        $this->formMode = true; // Show the form
    }
    #[On('part-saved')]
    public function refreshParts()
    {
        $this->loadAvailableItems();
        $this->formMode = false;
        $this->editingPartId = null;
    }






    // ==============================
    // Part Events
    // ==============================
    #[On('part-temp-added')]
    public function handlePartTempAdded($payload)
    {
        $type = $payload['type'];
        unset($payload['type']);

        $modelClass = PartsConfig::modelMap()[$type];

        $newModel = $modelClass::create([
            'brand' => $payload['brand'] ?? '',
            'model' => $payload['model'] ?? '',
            'system_unit_id' => null
        ]);

        $item = [
            'id' => $newModel->id,
            'brand' => $newModel->brand,
            'model' => $newModel->model
        ];

        $group = in_array($type, $this->componentTypes) ? 'components' : 'peripherals';

        if (!isset($this->unitSelections[$group][$type])) {
            $this->unitSelections[$group][$type] = [];
        }

        $this->unitSelections[$group][$type][] = $item;

        $this->loadAvailableItems();
        $this->formMode = false;
        $this->editingPartId = null;
    }
    #[On('part-saved')]
    public function handlePartSaved()
    {
        $this->loadAvailableItems();
        $this->formMode = false;
        $this->editingPartId = null;
    }

    // ==============================
    // Save Unit (Create or Update)
    // ==============================
    public function openCreateModal()
    {
        $this->reset([
            'unitId',
            'name',
            'status',
            'room_id',
            'unitSelections',
            'middleTab',
            'selectedComponentType',
            'selectedPeripheralType'
        ]);

        $this->modalMode = 'create';
        $this->dispatch('openModal');
    }

    public function save()
    {
        $this->validate();

        // Create or update the unit based on modal mode
        if ($this->modalMode === 'create') {
            $unit = SystemUnit::create([
                'name' => $this->name,
                'status' => $this->status,
                'room_id' => $this->room_id
            ]);
        } else {
            $unit = SystemUnit::findOrFail($this->unitId);
            $unit->update([
                'name' => $this->name,
                'status' => $this->status,
                'room_id' => $this->room_id
            ]);
        }

        // Always store the current ID
        $this->unitId = $unit->id;

        // Link components and peripherals
        foreach (['components', 'peripherals'] as $group) {
            foreach ($this->unitSelections[$group] as $type => &$items) {
                $ids = collect($items)->pluck('id')->filter()->all();
                if (!empty($ids)) {
                    $modelClass = PartsConfig::modelMap()[$type];
                    $modelClass::whereIn('id', $ids)
                        ->update(['system_unit_id' => $unit->id]);
                }
            }
        }

        session()->flash('success', 'System unit saved!');
        event(new UnitUpdated($unit));

        // Close modal only in edit mode
        if ($this->modalMode === 'edit') {
            $this->dispatch('closeModal');
        } else {
            // Reset for a fresh create form
            $this->resetExcept('rooms', 'componentTypes', 'peripheralTypes');
        }

        $this->loadAvailableItems();
    }



    // ==============================
    // Render
    // ==============================
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
