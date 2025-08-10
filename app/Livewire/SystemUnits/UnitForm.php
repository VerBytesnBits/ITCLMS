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
    ComputerCase
};

use App\Events\UnitUpdated;

class UnitForm extends Component
{
    public ?int $unitId = null;

    public $name;
    public $status = 'Operational';
    public $room_id;

    public $processor_id;
    public $cpu_cooler_id;
    public $motherboard_id;
    public $memory_id;
    public $graphics_card_id;
    public $drive_id;
    public $drive_type;
    public $power_supply_id;
    public $computer_case_id;

    public $rooms = [];
    public $processors = [];
    public $cpuCoolers = [];
    public $motherboards = [];
    public $memories = [];
    public $graphicsCards = [];
    public $drives = [];
    public $powerSupplies = [];
    public $computerCases = [];

    public $modalMode = 'create';

    protected $rules = [
        'name' => 'required|string|max:255',
        'status' => 'required|in:Operational,Needs Repair,Non-Operational',
        'room_id' => 'required|exists:rooms,id',
        'processor_id' => 'nullable|exists:processors,id',
        'cpu_cooler_id' => 'nullable|exists:cpu_coolers,id',
        'motherboard_id' => 'nullable|exists:motherboards,id',
        'memory_id' => 'nullable|exists:memories,id',
        'graphics_card_id' => 'nullable|exists:graphics_cards,id',
        'drive_id' => 'nullable',
        'drive_type' => 'nullable|in:m2,sata,hdd',
        'power_supply_id' => 'nullable|exists:power_supplies,id',
        'computer_case_id' => 'nullable|exists:computer_cases,id',
    ];

    protected array $statusMap = [
        'Operational' => 'Working',
        'Needs Repair' => 'Faulty',
        'Non-Operational' => 'Under Maintenance',
    ];

    protected array $reverseStatusMap = [];

    public function mount($unitId = null)
    {
        $this->reverseStatusMap = array_flip($this->statusMap);

        if ($unitId) {
            $unit = SystemUnit::findOrFail($unitId);

            $this->fill($unit->only([
                'name',
                'room_id',
                'processor_id',
                'cpu_cooler_id',
                'motherboard_id',
                'memory_id',
                'graphics_card_id',
                'drive_id',
                'drive_type',
                'power_supply_id',
                'computer_case_id',
                'status'
            ]));


            $this->unitId = $unitId;
            $this->modalMode = 'edit';
        }

        $this->loadDropdownData();
    }

    protected function loadDropdownData()
    {
        $excludeUnitId = $this->unitId;

        $this->rooms = Room::all();

        $componentMappings = [
            'processors' => ['model' => Processor::class, 'column' => 'processor_id'],
            'cpuCoolers' => ['model' => CpuCooler::class, 'column' => 'cpu_cooler_id'],
            'motherboards' => ['model' => Motherboard::class, 'column' => 'motherboard_id'],
            'memories' => ['model' => Memory::class, 'column' => 'memory_id'],
            'graphicsCards' => ['model' => GraphicsCard::class, 'column' => 'graphics_card_id'],
            'powerSupplies' => ['model' => PowerSupply::class, 'column' => 'power_supply_id'],
            'computerCases' => ['model' => ComputerCase::class, 'column' => 'computer_case_id'],
        ];

        foreach ($componentMappings as $prop => $data) {
            $column = $data['column'];
            $model = $data['model'];

            $this->$prop = $model::whereNotIn(
                'id',
                SystemUnit::whereNotNull($column)
                    ->when($excludeUnitId, fn($q) => $q->where('id', '!=', $excludeUnitId))
                    ->pluck($column)
            )
                ->orWhere('id', $this->$column ?? 0)
                ->get();
        }

        // Drives
        $usedM2 = SystemUnit::where('drive_type', 'm2')
            ->when($excludeUnitId, fn($q) => $q->where('id', '!=', $excludeUnitId))
            ->pluck('drive_id');

        $usedSata = SystemUnit::where('drive_type', 'sata')
            ->when($excludeUnitId, fn($q) => $q->where('id', '!=', $excludeUnitId))
            ->pluck('drive_id');

        $usedHdd = SystemUnit::where('drive_type', 'hdd')
            ->when($excludeUnitId, fn($q) => $q->where('id', '!=', $excludeUnitId))
            ->pluck('drive_id');

        $this->drives = collect()
            ->merge(M2Ssd::whereNotIn('id', $usedM2)->get()->map(fn($d) => $this->mapDrive($d, 'm2')))
            ->merge(SataSsd::whereNotIn('id', $usedSata)->get()->map(fn($d) => $this->mapDrive($d, 'sata')))
            ->merge(HardDiskDrive::whereNotIn('id', $usedHdd)->get()->map(fn($d) => $this->mapDrive($d, 'hdd')));
    }

    // ...

    public function save()
    {
        $this->validate();

        $statusMap = [
            'Operational' => 'Working',
            'Needs Repair' => 'Faulty',
            'Non-Operational' => 'Under Maintenance',
        ];
        $componentStatus = $statusMap[$this->status] ?? 'Working';

        if (strpos($this->drive_id, '|') !== false) {
            [$type, $id] = explode('|', $this->drive_id);
            $this->drive_type = $type;
            $this->drive_id = $id;
        }

        $mode = $this->unitId ? 'update' : 'create';
        $unit = $this->unitId ? SystemUnit::findOrFail($this->unitId) : new SystemUnit();

        $unit->fill([
            'name' => $this->name,
            'status' => $this->status,
            'room_id' => $this->room_id,
            'processor_id' => $this->processor_id,
            'cpu_cooler_id' => $this->cpu_cooler_id,
            'motherboard_id' => $this->motherboard_id,
            'memory_id' => $this->memory_id,
            'graphics_card_id' => $this->graphics_card_id,
            'power_supply_id' => $this->power_supply_id,
            'computer_case_id' => $this->computer_case_id,
            'drive_id' => $this->drive_id,
            'drive_type' => $this->drive_type,
        ])->save();

        $this->updateComponentStatus($componentStatus);
        event(new UnitUpdated($unit));

        // 🔥 Broadcast real-time update
        // broadcast(new SystemUnitUpdated($unit, $mode))->toOthers();

        session()->flash('success', 'System unit and components updated!');
        $this->resetInput();
    }



    private function updateComponentStatus($componentStatus)
    {
        if ($this->processor_id) {
            Processor::where('id', $this->processor_id)->update(['status' => $componentStatus]);
        }
        if ($this->cpu_cooler_id) {
            CpuCooler::where('id', $this->cpu_cooler_id)->update(['status' => $componentStatus]);
        }
        if ($this->motherboard_id) {
            Motherboard::where('id', $this->motherboard_id)->update(['status' => $componentStatus]);
        }
        if ($this->memory_id) {
            Memory::where('id', $this->memory_id)->update(['status' => $componentStatus]);
        }
        if ($this->graphics_card_id) {
            GraphicsCard::where('id', $this->graphics_card_id)->update(['status' => $componentStatus]);
        }
        if ($this->power_supply_id) {
            PowerSupply::where('id', $this->power_supply_id)->update(['status' => $componentStatus]);
        }
        if ($this->computer_case_id) {
            ComputerCase::where('id', $this->computer_case_id)->update(['status' => $componentStatus]);
        }

        // Drives
        if ($this->drive_type === 'm2') {
            M2Ssd::where('id', $this->drive_id)->update(['status' => $componentStatus]);
        } elseif ($this->drive_type === 'sata') {
            SataSsd::where('id', $this->drive_id)->update(['status' => $componentStatus]);
        } elseif ($this->drive_type === 'hdd') {
            HardDiskDrive::where('id', $this->drive_id)->update(['status' => $componentStatus]);
        }

        session()->flash('success', 'System unit and components updated!');
        $this->resetInput();
        $this->loadDropdownData();
    }



    public function resetInput()
    {
        $this->reset([
            'name',
            'status',
            'room_id',
            'unitId',
            'processor_id',
            'cpu_cooler_id',
            'motherboard_id',
            'memory_id',
            'graphics_card_id',
            'drive_id',
            'drive_type',
            'power_supply_id',
            'computer_case_id'
        ]);
        // $this->status = 'Operational';
        $this->modalMode = 'create';
    }

    protected function mapDrive($drive, string $type)
    {
        return (object) [
            'id' => $drive->id,
            'capacity' => $drive->capacity,
            'type' => $drive->type,
            'label' => "{$drive->type} {$drive->capacity}GB",
            'drive_type' => $type
        ];
    }

    public function render()
    {
        return view('livewire.system-units.unit-form', [
            'rooms' => $this->rooms,
            'processors' => $this->processors,
            'cpuCoolers' => $this->cpuCoolers,
            'motherboards' => $this->motherboards,
            'memories' => $this->memories,
            'graphicsCards' => $this->graphicsCards,
            'drives' => $this->drives,
            'powerSupplies' => $this->powerSupplies,
            'computerCases' => $this->computerCases,
            'statuses' => array_keys($this->statusMap),
            'm2Ssds' => M2Ssd::all(),
            'sataSsds' => SataSsd::all(),
            'hardDiskDrives' => HardDiskDrive::all(),
        ]);
    }
}
