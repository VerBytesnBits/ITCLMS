<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;
use App\Models\Room;
use Illuminate\Validation\Rule;
use App\Models\ComponentParts;
use App\Models\Peripheral;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class UnitForm extends Component
{
    public bool $show = false;
    public string $mode = 'create'; // create | edit
    public ?int $unitId = null;

    public ?string $category = null;
    public ?string $name = null;
    public ?string $serial_number = null;
    public ?string $status = null;
    public ?string $condition = null;
    public ?int $room_id = null;

    public $quantity = 1;

    public $unit = [
        'unit_name' => null,
        'room_id' => null,
        'status' => 'In Use',
    ];

    //  Temp arrays for child component data
    public $tempComponents = [];
    public $tempPeripherals = [];



    /** Child listener handlers */
    #[On('tempComponentAdded')]
    public function addTempComponent(array $component)
    {
        $this->tempComponents[] = $component;
    }

    #[On('tempPeripheralAdded')]
    public function addTempPeripheral(array $peripheral)
    {
        $this->tempPeripherals[] = $peripheral;
    }

    #[On('remove-temp-component')]
    public function removeTempComponent($index)
    {
        if (!is_numeric($index)) {
            return; // prevents illegal offset crash
        }

        unset($this->tempComponents[(int) $index]);
        $this->tempComponents = array_values($this->tempComponents);
    }

    #[On('remove-temp-peripheral')]
    public function removeTempPeripheral($index)
    {
        if (!is_numeric($index)) {
            return;
        }

        unset($this->tempPeripherals[(int) $index]);
        $this->tempPeripherals = array_values($this->tempPeripherals);
    }


    protected function rules(): array
    {
        return [
            'category' => 'required|string|in:PC,SERVER,LAPTOP',
            'name' => 'nullable|string|max:100',
            'serial_number' => [
                'nullable',
                'string',
                Rule::unique('system_units', 'serial_number')->ignore($this->unitId),
            ],
            'status' => 'required|string',
            'room_id' => 'required|exists:rooms,id',
            'quantity' => 'required|integer|min:1',
        ];
    }

    public function create()
    {
        $this->resetValidation();
        $this->reset([
            'unitId',
            'category',
            'name',
            'serial_number',
            'status',
            'condition',
            'room_id',
            'quantity',
        ]);

        $this->mode = 'create';
        $this->show = true;

        // clear temp arrays
        $this->tempComponents = [];
        $this->tempPeripherals = [];
    }

    public function mount(?int $unitId = null, string $mode = 'create', bool $show = false)
    {
        $this->unitId = $unitId;
        $this->mode = $mode;
        $this->show = $show;

        if ($unitId && $mode === 'edit') {
            $unit = SystemUnit::with(['components', 'peripherals'])->findOrFail($unitId);

            $this->fill([
                'unitId' => $unit->id,
                'name' => $unit->name,
                'serial_number' => $unit->serial_number,
                'status' => $unit->status,
                'category' => preg_replace('/\d+$/', '', $unit->name),
                'room_id' => $unit->room_id,
            ]);

            // pre-load components/peripherals into temp arrays for editing
            foreach ($unit->components as $component) {
                $this->tempComponents[] = $component->toArray();
            }
            foreach ($unit->peripherals as $peripheral) {
                $this->tempPeripherals[] = $peripheral->toArray();
            }
        }
    }

    // public $inlineSelectedPartFromChild;

    // public function setPeripheralType($value)
    // {
    //     $this->inlineSelectedPartFromChild = $value;
    // }
    private function normalizePeripheral(array $peripheral, SystemUnit $unit): array
    {
        return [
            'type' => $peripheral['type'] ?? 'Unknown',
            'brand' => $peripheral['brand'] ?? null,
            'model' => $peripheral['model'] ?? null,
            'serial_number' => $peripheral['serial_number'] ?? null,
            'connection_type' => $peripheral['connection_type'] ?? null,
            'status' => 'In Use',
            'room_id' => $unit->room_id,
            'system_unit_id' => $unit->id,
        ];
    }


    public function save()
    {
        $this->validate();

        if (
            $this->mode === 'create' &&
            empty($this->tempComponents) &&
            empty($this->tempPeripherals)
        ) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Incomplete Setup',
                'text' => 'Please add at least one component or peripheral.',
            ]);
            return;
        }



        DB::transaction(function () {

            [$startNumber] = $this->getNextIndex();

            // MULTIPLE UNIT CREATION
            if ($this->mode === 'create' && $this->quantity > 1) {
                for ($i = 0; $i < $this->quantity; $i++) {
                    $unitNumber = $startNumber + $i;
                    $unitName = $this->category . $unitNumber;
                    $serial = $this->generateSerialSequential($unitNumber);

                    $unit = SystemUnit::create([
                        'name' => $unitName,
                        'category' => $this->category,
                        'serial_number' => $serial,
                        'status' => $this->status,
                        'room_id' => $this->room_id,
                    ]);

                    // ASSIGN TEMP COMPONENTS
                    foreach ($this->tempComponents as $component) {
                        $component['system_unit_id'] = $unit->id;
                        $component['room_id'] = $unit->room_id;
                        $component['status'] = 'In Use';
                        ComponentParts::create($component);
                    }

                    // ASSIGN TEMP PERIPHERALS
                    foreach ($this->tempPeripherals as $peripheral) {
                        Peripheral::create(
                            $this->normalizePeripheral($peripheral, $unit)
                        );

                    }
                }

                $this->dispatch('swal', [
                    'toast' => true,
                    'icon' => 'success',
                    'title' => "{$this->quantity} system units created with components & peripherals",
                    'timer' => 3000,
                ]);
            }

            // SINGLE UNIT CREATION
            elseif ($this->mode === 'create') {
                $unitName = $this->name ?: $this->category . $startNumber;
                $serial = $this->serial_number ?: $this->generateSerialSequential($startNumber);

                $unit = SystemUnit::create([
                    'name' => $unitName,
                    'category' => $this->category,
                    'serial_number' => $serial,
                    'status' => $this->status,
                    'room_id' => $this->room_id,
                ]);

                foreach ($this->tempComponents as $component) {
                    $component['system_unit_id'] = $unit->id;
                    $component['room_id'] = $unit->room_id;
                    $component['status'] = 'In Use';
                    ComponentParts::create($component);
                }

                foreach ($this->tempPeripherals as $peripheral) {
                    Peripheral::create(
                        $this->normalizePeripheral($peripheral, $unit)
                    );

                }

                // ✅ Get Lab Name (Optional but Recommended)
                $labName = optional($unit->room)->name ?? 'Lab';

                // ✅ Correct Swal Message
                $this->dispatch('swal', [
                    'toast' => true,
                    'icon' => 'success',
                    'title' => "{$unitName} - {$labName} added successfully!",
                    'timer' => 3000,
                ]);
            }

            // UPDATE MODE
            else {
                $unitName = $this->name ?: $this->category . $startNumber;
                $unit = SystemUnit::findOrFail($this->unitId);
                $unit->update([
                    'name' => $this->name,
                    'serial_number' => $this->serial_number,
                    'status' => $this->status,
                    'room_id' => $this->room_id,
                ]);

                // Update / assign components & peripherals
                foreach ($this->tempComponents as $component) {
                    if (isset($component['id'])) {
                        $existing = ComponentParts::find($component['id']);
                        $existing->update($component);
                    } else {
                        $component['system_unit_id'] = $unit->id;
                        $component['room_id'] = $unit->room_id;
                        $component['status'] = 'In Use';
                        ComponentParts::create($component);
                    }
                }

                foreach ($this->tempPeripherals as $peripheral) {
                    if (isset($peripheral['id'])) {
                        $existing = Peripheral::find($peripheral['id']);
                        $existing->update($peripheral);
                    } else {
                        $peripheral['system_unit_id'] = $unit->id;
                        Peripheral::create(
                            $this->normalizePeripheral($peripheral, $unit)
                        );

                    }
                }


                $labName = optional($unit->room)->name ?? 'Lab';
                $this->dispatch('swal', [
                    'toast' => true,
                    'icon' => 'success',
                    'title' => "{$unitName} - {$labName} updated successfully",
                    'timer' => 3000,
                ]);
            }

        });

        // Clear temp arrays
        $this->tempComponents = [];
        $this->tempPeripherals = [];

        $this->dispatch($this->mode === 'create' ? 'unitCreated' : 'unitUpdated');
        $this->dispatch('closeModal');
    }

    /** ---- Helpers ---- */

    private function getNextIndex(): array
    {
        $lastUnit = SystemUnit::where('room_id', $this->room_id)
            ->where('name', 'LIKE', "{$this->category}%")
            ->orderByRaw("CAST(SUBSTRING(name, LENGTH(?) + 1) AS UNSIGNED) DESC", [$this->category])
            ->first();

        preg_match('/\d+$/', $lastUnit->name ?? '', $matches);
        $startNumber = isset($matches[0]) ? ((int) $matches[0] + 1) : 1;

        return [$startNumber, $lastUnit];
    }

    protected function generateSerialSequential(int $i): string
    {
        $room = Room::find($this->room_id);
        $catPrefix = strtoupper(substr($this->category, 0, 3));
        $roomPrefix = '';

        if ($room && !empty($room->name)) {
            $name = strtoupper($room->name);
            if (preg_match('/^([A-Z])[A-Z]*[- ]?(\d+)$/', $name, $m)) {
                $roomPrefix = $m[1] . $m[2];
            } else {
                $roomPrefix = substr(str_replace([' ', '-'], '', $name), 0, 2);
            }
        }

        return sprintf('%s%s-%03d', $catPrefix, $roomPrefix, $i);
    }

    public function updatedCategory()
    {
        $this->generateUnitName();
    }
    public function updatedRoomId()
    {
        $this->generateUnitName();
    }

    public function generateUnitName(): void
    {
        $this->name = null;
        if (!$this->category || !$this->room_id)
            return;

        [$nextNumber] = $this->getNextIndex();
        $this->name = $this->category . $nextNumber;

        if ($this->quantity === 1) {
            $this->serial_number = $this->generateSerialSequential($nextNumber);
        }
    }

    public function updatedQuantity()
    {
        if ($this->quantity > 1) {
            $this->serial_number = null;
        } else {
            $this->generateUnitName();
        }
    }

    public function render()
    {
        return view('livewire.system-units.unit-form', [
            'rooms' => Room::all(),
        ]);
    }
}
