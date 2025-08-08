<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;
use App\Models\Room;

class UnitForm extends Component
{
    public ?int $unitId = null;
    public $unit;
    public $name;
    public $status = 'Working';
    public $room_id;

    public $rooms;

    public $modalMode = 'create'; // 'create' or 'edit'

    protected $rules = [
        'name' => 'required|string|max:255',
        'status' => 'required|in:Working,Under Maintenance,Decommissioned',
        'room_id' => 'required|exists:rooms,id',
    ];

    public function mount($unitId = null)
    {
        $this->rooms = Room::all();

        if ($unitId) {
            $this->unitId = $unitId;
            $unit = SystemUnit::findOrFail($unitId);

            $this->name = $unit->name;
            $this->status = $unit->status;
            $this->room_id = $unit->room_id;
            $this->modalMode = 'edit';
        } else {
            $this->room_id = $this->rooms->first()?->id;
            $this->modalMode = 'create';
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->modalMode === 'create') {
            $unit = SystemUnit::create([
                'name' => $this->name,
                'status' => $this->status,
                'room_id' => $this->room_id,
            ]);

            $this->createDefaultComponents($unit);
            $this->createDefaultPeripherals($unit);

            $this->dispatch('unitCreated');
            session()->flash('success', 'System Unit created successfully.');
        } else {
            $unit = SystemUnit::findOrFail($this->unitId);
            $unit->update([
                'name' => $this->name,
                'status' => $this->status,
                'room_id' => $this->room_id,
            ]);

            $this->dispatch('unitUpdated');
            session()->flash('success', 'System Unit updated successfully.');
        }

        $this->resetInput();
        $this->dispatch('closeModal');
    }


    public function resetInput()
    {
        $this->reset(['name', 'status', 'room_id', 'unitId']);
    }

    public function render()
    {
        return view('livewire.system-units.unit-form', [
            'rooms' => $this->rooms,
        ]);
    }


    protected function createDefaultComponents(SystemUnit $unit)
    {
        // Processor
        $unit->processors()->create([
            'brand' => 'Intel',
            'model' => 'Core i5-10400',
            'type' => 'CPU',
            'base_clock' => 2.9, // GHz
            'boost_clock' => 4.3, // GHz (optional)
            'status' => 'Working',
            'serial_number' => 'DEFAULT12345',
            'date_purchased' => now()->subYear(),
            'notes' => 'Default processor',
        ]);

        // CPU Cooler
        $unit->cpuCoolers()->create([
            'brand' => 'CoolerMaster',
            'model' => 'Hyper 212',
            'status' => 'Working',
            'serial_number' => 'CM123456',
            'date_purchased' => now()->subYear(),
            'notes' => 'Default CPU cooler',
        ]);

        // Motherboard
        $unit->motherboards()->create([
            'brand' => 'ASUS',
            'model' => 'Prime B460M-A',
            'status' => 'Working',
            'serial_number' => 'MB987654',
            'date_purchased' => now()->subYear(),
            'notes' => 'Default motherboard',
        ]);

        // Memory (RAM)
        $unit->memories()->create([
            'brand' => 'Corsair',
            'model' => 'Vengeance LPX',
            'type' => 'DDR4',
            'capacity' => 16,
            'status' => 'Working',
            'serial_number' => 'RAM123456',
            'date_purchased' => now()->subYear(),
            'notes' => 'Default RAM module',
        ]);

        // Graphics Card
        $unit->graphicsCards()->create([
            'brand' => 'NVIDIA',
            'model' => 'GTX 1660 Super',
            'base_clock' => 1530, // MHz
            'boost_clock' => 1785, // MHz (optional)
            'status' => 'Working',
            'serial_number' => 'GPU123456',
            'date_purchased' => now()->subYear(),
            'notes' => 'Default graphics card',
        ]);

        // M.2 SSD
        $unit->m2Ssds()->create([
            'brand' => 'Samsung',
            'model' => '970 EVO',
            'type' => 'M.2 NVMe SSD',
            'capacity' => 500,
            'status' => 'Working',
            'serial_number' => 'M2SSD1234',
            'date_purchased' => now()->subYear(),
            'notes' => 'Default M.2 SSD',
        ]);

        // SATA SSD
        $unit->sataSsds()->create([
            'brand' => 'Crucial',
            'model' => 'MX500',
            'type' => 'SATA SSD',
            'capacity' => 500,
            'status' => 'Working',
            'serial_number' => 'SATASSD1234',
            'date_purchased' => now()->subYear(),
            'notes' => 'Default SATA SSD',
        ]);

        // Hard Disk Drive
        $unit->hardDiskDrives()->create([
            'brand' => 'Seagate',
            'model' => 'Barracuda',
            'type' => 'HDD',
            'capacity' => 1000,
            'status' => 'Working',
            'serial_number' => 'HDD123456',
            'date_purchased' => now()->subYear(),
            'notes' => 'Default HDD',
        ]);

        // Power Supply
        $unit->powerSupplies()->create([
            'brand' => 'EVGA',
            'model' => '500W Bronze',
            'status' => 'Working',
            'serial_number' => 'PSU123456',
            'date_purchased' => now()->subYear(),
            'notes' => 'Default PSU',
        ]);

        // Computer Case
        $unit->computerCase()->create([
            'brand' => 'NZXT',
            'model' => 'H510',
            'status' => 'Working',
            'serial_number' => 'CASE0001',
            'date_purchased' => now()->subYear(),
            'notes' => 'Default computer case',
        ]);
    }

    protected function createDefaultPeripherals(SystemUnit $unit)
    {
        // Default keyboard
        $unit->keyboard()->create([
            'brand' => 'Logitech',
            'model' => 'K120',
            'status' => 'Working',
            'serial_number' => 'KEY1234',
            'date_purchased' => now()->subYear(),
            'notes' => 'Default keyboard',
        ]);

        // Default mouse
        $unit->mouse()->create([
            'brand' => 'Logitech',
            'model' => 'M185',
            'status' => 'Working',
            'serial_number' => 'MOUSE1234',
            'date_purchased' => now()->subYear(),
            'notes' => 'Default mouse',
        ]);

        // Default headset
        $unit->headset()->create([
            'brand' => 'Sony',
            'model' => 'WH-CH510',
            'status' => 'Working',
            'serial_number' => 'HEADSET001',
            'date_purchased' => now()->subYear(),
            'notes' => 'Default headset',
        ]);

        // Default speaker
        $unit->speaker()->create([
            'brand' => 'Creative',
            'model' => 'Pebble 2.0',
            'status' => 'Working',
            'serial_number' => 'SPKR1234',
            'date_purchased' => now()->subYear(),
            'notes' => 'Default speakers',
        ]);

        // Default webcam
        $unit->webCamera()->create([
            'brand' => 'Logitech',
            'model' => 'C270',
            'status' => 'Working',
            'serial_number' => 'WEBCAM5678',
            'date_purchased' => now()->subYear(),
            'notes' => 'Default webcam',
        ]);

        // Default monitor
        $unit->monitor()->create([
            'brand' => 'Dell',
            'model' => 'P2419H',
            'status' => 'Working',
            'serial_number' => 'MON12345',
            'date_purchased' => now()->subYear(),
            'notes' => 'Default monitor',
        ]);
    }

}
