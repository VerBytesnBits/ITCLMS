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

    public array $componentTypes = [
        'processors',
        'cpu_coolers',
        'motherboards',
        'memories',
        'graphics_cards',
        'm2_ssds',
        'sata_ssds',
        'hard_disk_drives',
        'power_supplies',
        'computer_cases'
    ];

    public array $peripheralTypes = [
        'monitors',
        'keyboards',
        'mice',
        'headsets',
        'speakers',
        'web_cameras'
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
            $unit = SystemUnit::with([
                'processor',
                'cpuCooler',
                'motherboard',
                'memory',
                'graphicsCard',
                'm2Ssd',
                'sataSsd',
                'hardDiskDrive',
                'powerSupply',
                'computerCase',
                'monitor',
                'keyboard',
                'mouse',
                'headset',
                'speaker',
                'webCamera',
            ])->findOrFail($unitId);

            $this->fill([
                'name' => $unit->name,
                'status' => $unit->status,
                'room_id' => $unit->room_id,
                'unitId' => $unitId
            ]);

            foreach ($this->componentTypes as $type) {
                if ($relation = $unit->{\Str::camel($type)}) {
                    $this->unitSelections['components'][$type] = collect(is_iterable($relation) ? $relation : [$relation])
                        ->map(fn($item) => $item->only(['id', 'brand', 'model']))
                        ->toArray();
                }
            }

            foreach ($this->peripheralTypes as $type) {
                if ($relation = $unit->{\Str::camel($type)}) {
                    $this->unitSelections['peripherals'][$type] = collect(is_iterable($relation) ? $relation : [$relation])
                        ->map(fn($item) => $item->only(['id', 'brand', 'model']))
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
        if (!$room) return;

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
        $this->availableComponents = [
            'processors' => Processor::all()->toArray(),
            'cpu_coolers' => CpuCooler::all()->toArray(),
            'motherboards' => Motherboard::all()->toArray(),
            'memories' => Memory::all()->toArray(),
            'graphics_cards' => GraphicsCard::all()->toArray(),
            'm2_ssds' => M2Ssd::all()->toArray(),
            'sata_ssds' => SataSsd::all()->toArray(),
            'hard_disk_drives' => HardDiskDrive::all()->toArray(),
            'power_supplies' => PowerSupply::all()->toArray(),
            'computer_cases' => ComputerCase::all()->toArray(),
        ];

        $this->availablePeripherals = [
            'monitors' => Display::all()->toArray(),
            'keyboards' => Keyboard::all()->toArray(),
            'mice' => Mouse::all()->toArray(),
            'headsets' => Headset::all()->toArray(),
            'speakers' => Speaker::all()->toArray(),
            'web_cameras' => WebDigitalCamera::all()->toArray(),
        ];
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

    public function addToUnit($type, $id)
    {
        $list = $this->middleTab === 'components' ? $this->availableComponents : $this->availablePeripherals;
        $item = collect($list[$type] ?? [])->firstWhere('id', $id);

        if ($item) {
            $this->unitSelections[$this->middleTab][$type][] = $item;
        }
    }

    public function removeFromUnit($type, $id)
    {
        $this->unitSelections[$this->middleTab][$type] = array_filter(
            $this->unitSelections[$this->middleTab][$type] ?? [],
            fn($i) => $i['id'] != $id
        );
    }

    public function save()
    {
        $this->validate();

        $unit = $this->unitId ? SystemUnit::findOrFail($this->unitId) : new SystemUnit();
        $unit->fill([
            'name' => $this->name,
            'status' => $this->status,
            'room_id' => $this->room_id,
        ])->save();

        foreach ($this->unitSelections['components'] as $type => $items) {
            $relation = \Str::camel($type);
            $unit->$relation()->sync(collect($items)->pluck('id')->toArray());
        }

        foreach ($this->unitSelections['peripherals'] as $type => $items) {
            $relation = \Str::camel($type);
            $unit->$relation()->sync(collect($items)->pluck('id')->toArray());
        }

        event(new UnitUpdated($unit));

        session()->flash('success', 'System unit saved!');
        $this->resetExcept('rooms', 'componentTypes', 'peripheralTypes');
        $this->loadAvailableItems();
    }

    private function modelMap()
    {
        return [
            'processors' => Processor::class,
            'cpu_coolers' => CpuCooler::class,
            'motherboards' => Motherboard::class,
            'memories' => Memory::class,
            'graphics_cards' => GraphicsCard::class,
            'm2_ssds' => M2Ssd::class,
            'sata_ssds' => SataSsd::class,
            'hard_disk_drives' => HardDiskDrive::class,
            'power_supplies' => PowerSupply::class,
            'computer_cases' => ComputerCase::class,
            'monitors' => Display::class,
            'keyboards' => Keyboard::class,
            'mice' => Mouse::class,
            'headsets' => Headset::class,
            'speakers' => Speaker::class,
            'web_cameras' => WebDigitalCamera::class,
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
