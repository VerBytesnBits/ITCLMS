<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;
use App\Models\Room;
use App\Models\Processor;
use App\Models\CpuCooler;
use App\Models\Motherboard;
use App\Models\Memory;
use App\Models\GraphicsCard;
use App\Models\M2Ssd;
use App\Models\SataSsd;
use App\Models\HardDiskDrive;
use App\Models\PowerSupply;
use App\Models\ComputerCase;

class UnitForm extends Component
{
    public ?int $unitId = null;
    public $name;
    public $status = 'Working';
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

    public $rooms;
    public $processors;
    public $cpuCoolers;
    public $motherboards;
    public $memories;
    public $graphicsCards;
    public $drives;
    public $powerSupplies;
    public $computerCases;

    public $modalMode = 'create';

    protected $rules = [
        'name' => 'required|string|max:255',
        'status' => 'required|in:Working,Under Maintenance,Decommissioned',
        'room_id' => 'required|exists:rooms,id',
        'processor_id' => 'nullable|exists:processors,id',
        'cpu_cooler_id' => 'nullable|exists:cpu_coolers,id',
        'motherboard_id' => 'nullable|exists:motherboards,id',
        'memory_id' => 'nullable|exists:memories,id',
        'graphics_card_id' => 'nullable|exists:graphics_cards,id',
        'drive_id' => 'nullable|integer',
        'drive_type' => 'nullable|in:m2,sata,hdd',
        'power_supply_id' => 'nullable|exists:power_supplies,id',
        'computer_case_id' => 'nullable|exists:computer_cases,id',
    ];

    public function mount($unitId = null)
    {
        $this->rooms = Room::all();
        $this->processors = Processor::all();
        $this->cpuCoolers = CpuCooler::all();
        $this->motherboards = Motherboard::all();
        $this->memories = Memory::all();
        $this->graphicsCards = GraphicsCard::all();
        $this->powerSupplies = PowerSupply::all();
        $this->computerCases = ComputerCase::all();

        $this->drives = collect()
            ->merge(M2Ssd::all()->map(fn($d) => (object) [
                'id' => $d->id,
                'capacity' => $d->capacity,
                'type' => $d->type,
                'label' => "{$d->type} {$d->capacity}GB",
                'drive_type' => 'm2',
            ]))
            ->merge(SataSsd::all()->map(fn($d) => (object) [
                'id' => $d->id,
                'capacity' => $d->capacity,
                'type' => $d->type,
                'label' => "{$d->type} {$d->capacity}GB",
                'drive_type' => 'sata',
            ]))
            ->merge(HardDiskDrive::all()->map(fn($d) => (object) [
                'id' => $d->id,
                'capacity' => $d->capacity,
                'type' => $d->type,
                'label' => "{$d->type} {$d->capacity}GB",
                'drive_type' => 'hdd',
            ]));

        if ($unitId) {
            $unit = SystemUnit::findOrFail($unitId);
            $this->fill($unit->only([
                'name',
                'status',
                'room_id',
                'processor_id',
                'cpu_cooler_id',
                'motherboard_id',
                'memory_id',
                'graphics_card_id',
                'drive_id',
                'drive_type',
                'power_supply_id',
                'computer_case_id'
            ]));
            $this->unitId = $unitId;
            $this->modalMode = 'edit';
        } else {
            $this->room_id = $this->rooms->isNotEmpty() ? $this->rooms->first()->id : null;
            $this->modalMode = 'create';
        }
    }

    public function updatedDriveId($value)
    {
        // $value is just the drive id (number)
        $drive = $this->drives->first(fn($d) => $d->id == $value);
        $this->drive_type = $drive->drive_type ?? null;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Operational,Needs Repair,Non-Operational',
            'room_id' => 'required|exists:rooms,id',
            'drive_id' => 'required', // actually type|id from dropdown
        ]);

        // If drive_id is coming as "type|id", split it
        if (strpos($this->drive_id, '|') !== false) {
            [$type, $id] = explode('|', $this->drive_id);
            $this->drive_type = $type;
            $this->drive_id = $id;
        }

        $unit = new SystemUnit();
        $unit->name = $this->name;
        $unit->status = $this->status;
        $unit->room_id = $this->room_id;
        $unit->processor_id = $this->processor_id;
        $unit->cpu_cooler_id = $this->cpu_cooler_id;
        $unit->motherboard_id = $this->motherboard_id;
        $unit->memory_id = $this->memory_id;
        $unit->graphics_card_id = $this->graphics_card_id;
        $unit->power_supply_id = $this->power_supply_id;
        $unit->computer_case_id = $this->computer_case_id;
        $unit->drive_id = $this->drive_id;     // numeric ID
        $unit->drive_type = $this->drive_type; // m2, sata, hdd
        $unit->save();

        session()->flash('success', 'System unit created!');
        $this->resetInput();
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
        $this->modalMode = 'create';
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
            'm2Ssds' => M2Ssd::all(),
            'sataSsds' => SataSsd::all(),
            'hardDiskDrives' => HardDiskDrive::all(),
            'units' => SystemUnit::with(['m2Ssd', 'sataSsd', 'hardDiskDrive'])->get(),
        ]);
    }
}