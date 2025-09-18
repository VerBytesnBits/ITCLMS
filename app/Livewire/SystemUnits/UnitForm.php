<?php

namespace App\Livewire\SystemUnits;

use Livewire\Component;
use App\Models\SystemUnit;
use App\Models\Room;
use Illuminate\Validation\Rule;

class UnitForm extends Component
{
    public bool $show = false;
    public string $mode = 'create'; // create | edit
    public ?int $unitId = null;

    public ?string $category = null;
    public ?string $name = null;
    public ?string $serial_number = null;
    public string $status = 'Operational';
    public string $condition = 'Operational';
    public ?int $room_id = null;
    public int $quantity = 1;
    public bool $multiple = false;

    protected function rules(): array
    {
        return [
            'category' => 'required|string|in:PC,SERVER,LAPTOP',
            'name' => 'required|string|max:100',
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

    /** ---------- Modal Control ---------- */
    /** Open Create Mode */
    public function create()
    {
        $this->resetValidation();
        $this->reset([
            'unitId',
            'category',
            'name',
            'serial_number',
            'status',
            'room_id',
            'quantity',
        ]);

        $this->multiple = false;   // default single mode
        $this->mode = 'create';
        $this->show = true;
    }

    /** Open Edit Mode */
    public function edit(SystemUnit $unit)
    {
        $this->resetValidation();

        $this->unitId = $unit->id;
        $this->category = preg_replace('/\d+$/', '', $unit->name);
        $this->name = $unit->name;
        $this->serial_number = $unit->serial_number;
        $this->status = $unit->status;
        $this->condition = $unit->condition;
        $this->room_id = $unit->room_id;
        $this->quantity = 1;

        $this->multiple = false;   // disable multiple in edit mode
        $this->mode = 'edit';
        $this->show = true;
    }


    public function close(): void
    {
        $this->show = false;
    }

    /** ---------- Save Logic ---------- */
    public function save()
    {
        $this->validate();

        $lastUnit = SystemUnit::where('room_id', $this->room_id)
            ->where('name', 'LIKE', $this->category . '%')
            ->orderByRaw("CAST(SUBSTRING(name, LENGTH(?) + 1) AS UNSIGNED) DESC", [$this->category])
            ->first();

        preg_match('/\d+$/', $lastUnit->name ?? '', $matches);
        $startNumber = isset($matches[0]) ? ((int) $matches[0] + 1) : 1;

        if ($this->mode === 'create') {
            if ($this->multiple) {
                // batch create
                for ($i = 0; $i < $this->quantity; $i++) {
                    $unitName = $this->category . ($startNumber + $i);
                    SystemUnit::create([
                        'name' => $unitName,
                        'category' => $this->category,
                        'serial_number' => strtoupper($unitName) . '-SN', // auto-gen serial
                        'status' => $this->status,
                        'room_id' => $this->room_id,
                    ]);
                }
            } else {
                // single create
                $unitName = $this->name ?: $this->category . $startNumber;
                SystemUnit::create([
                    'name' => $unitName,
                    'category' => $this->category,
                    'serial_number' => $this->serial_number,
                    'status' => $this->status,
                    'room_id' => $this->room_id,
                ]);
            }
        } else {
            // edit mode
            SystemUnit::find($this->unitId)?->update([
                'name' => $this->name,
                'category' => $this->category,
                'serial_number' => $this->serial_number,
                'status' => $this->status,
                'room_id' => $this->room_id,
            ]);
        }

        $this->dispatch('closeModal');
    }

    /** ---------- Auto-generate Unit Name ---------- */
    public function updatedCategory(): void
    {
        $this->generateUnitName();
    }

    public function updatedRoomId(): void
    {
        $this->generateUnitName();
    }

    protected function generateUnitName(): void
    {
        if (!$this->category || !$this->room_id) {
            $this->name = null;
            return;
        }

        $lastUnit = SystemUnit::where('room_id', $this->room_id)
            ->where('name', 'LIKE', $this->category . '%')
            ->orderByRaw("CAST(SUBSTRING(name, LENGTH(?) + 1) AS UNSIGNED) DESC", [$this->category])
            ->first();

        if ($lastUnit) {
            preg_match('/\d+$/', $lastUnit->name, $matches);
            $nextNumber = isset($matches[0]) ? ((int) $matches[0] + 1) : 1;
        } else {
            $nextNumber = 1;
        }

        $this->name = $this->category . $nextNumber;
    }

    /** ---------- Serial Generator ---------- */
    protected function generateSerial(int $i, int $startNumber): string
    {
        return strtoupper(substr($this->category, 0, 3)) . '-' . str_pad($startNumber + $i, 3, '0', STR_PAD_LEFT);
    }

    public function render()
    {
        return view('livewire.system-units.unit-form', [
            'rooms' => Room::all(),
        ]);
    }
}
