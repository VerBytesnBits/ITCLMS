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
    public string $status = 'Non-operational';
    public string $condition = 'Good'; // ✅ corrected default
    public ?int $room_id = null;
    public int $quantity = 1;
    public bool $multiple = false;

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
            'condition' => 'nullable|string',
            'room_id' => 'required|exists:rooms,id',
            'quantity' => 'required|integer|min:1',
        ];
    }
    protected function formData(): array
    {
        return $this->only([
            'name',
            'serial_number',
            'status',
            'room_id',
        ]);
    }

    /** ---------- Modal Control ---------- */
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
            'quantity'
        ]);

        $this->status = 'Operational';
        $this->condition = 'Good';
        $this->multiple = false;
        $this->mode = 'create';
        $this->show = true;
    }

    public function mount(?int $unitId = null, string $mode = 'create', bool $show = false)
    {
        $this->unitId = $unitId;
        $this->mode = $mode;
        $this->show = $show;

        if ($unitId && $mode === 'edit') {
            $unit = SystemUnit::findOrFail($unitId);
            $this->fill([
                'unitId' => $unit->id,
                'name' => $unit->name,
                'serial_number' => $unit->serial_number,
                'status' => $unit->status,
                'category' => preg_replace('/\d+$/', '', $unit->name),
                'room_id' => $unit->room_id,
            ]);
        }
    }

    public function close(): void
    {
        $this->show = false;
    }

    public function messages()
    {
        return [
            'room_id.required' => 'Please select a room.',
            'room_id.exists' => 'The selected room does not exist.',
        ];
    }

    public function updated($field)
    {
        $this->validateOnly($field);
    }
    /** ---------- Save Logic ---------- */
    public function save()
    {
        try {
            $this->validate();

            [$startNumber] = $this->getNextIndex();

            if ($this->mode === 'create') {
                if ($this->multiple) {
                    for ($i = 0; $i < $this->quantity; $i++) {
                        $unitNumber = $startNumber + $i;
                        $unitName = $this->category . $unitNumber;
                        $serial = $this->generateSerial($unitNumber);

                        SystemUnit::create([
                            'name' => $unitName,
                            'category' => $this->category,
                            'serial_number' => $serial,
                            'status' => $this->status,

                            'room_id' => $this->room_id,
                        ]);
                    }

                    $this->dispatch('swal', [
                        'toast' => true,
                        'icon' => 'success',
                        'title' => "{$this->quantity} system units created successfully",
                        'timer' => 3000,
                    ]);
                } else {
                    $unitName = $this->name ?: $this->category . $startNumber;
                    $serial = $this->serial_number ?: $this->generateSerial();

                    SystemUnit::create([
                        'name' => $unitName,
                        'category' => $this->category,
                        'serial_number' => $serial,
                        'status' => $this->status,

                        'room_id' => $this->room_id,
                    ]);

                    $this->dispatch('swal', [
                        'toast' => true,
                        'icon' => 'success',
                        'title' => 'System unit created successfully',
                        'timer' => 3000,
                    ]);
                }

                $this->dispatch('unitCreated');
            } else {
                $unit = SystemUnit::findOrFail($this->unitId);
                $unit->update([
                    'name' => $this->name,
                    'serial_number' => $this->serial_number,
                    'status' => $this->status,

                    'room_id' => $this->room_id,
                ]);

                $this->dispatch('swal', [
                    'toast' => true,
                    'icon' => 'success',
                    'title' => 'System unit updated successfully',
                    'timer' => 3000,
                ]);

                $this->dispatch('unitUpdated');
            }

            // Close modal
            $this->dispatch('closeModal');

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
            $errors = $e->validator->errors()->all();

            $this->dispatch('swal', [
                'toast' => true,
                'icon' => 'error',
                'title' => implode(' ', $errors),
                'timer' => 3000,
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'toast' => true,
                'icon' => 'error',
                'title' => 'Something went wrong: ' . $e->getMessage(),
                'timer' => 3000,
            ]);
        }
    }


    /** ---------- Helpers ---------- */
    private function getNextIndex(): array
    {
        $lastUnit = SystemUnit::where('room_id', $this->room_id)
            ->where('name', 'LIKE', $this->category . '%')
            ->orderByRaw("CAST(SUBSTRING(name, LENGTH(?) + 1) AS UNSIGNED) DESC", [$this->category])
            ->first();

        preg_match('/\d+$/', $lastUnit->name ?? '', $matches);
        $startNumber = isset($matches[0]) ? ((int) $matches[0] + 1) : 1;

        return [$startNumber, $lastUnit];
    }


    protected function generateSerial(int $i = 0, ?int $startNumber = null): string
    {
        $room = Room::find($this->room_id);

        // Category prefix (e.g. PC, LAP, SER)
        $categoryPrefix = strtoupper(substr($this->category ?? 'PC', 0, 3));

        // Build room prefix (e.g. LAB-1 → L1)
        $roomPrefix = '';
        if ($room && !empty($room->name)) {
            $name = strtoupper($room->name);
            if (preg_match('/^([A-Z])[A-Z]*[- ]?(\d+)$/', $name, $matches)) {
                $roomPrefix = $matches[1] . $matches[2];
            } else {
                $roomPrefix = substr(str_replace([' ', '-'], '', $name), 0, 2);
            }
        }

        // Final prefix e.g. "PCL1"
        $prefix = $categoryPrefix . $roomPrefix;

        // 🔑 If no startNumber given → get max suffix from existing serials
        if ($startNumber === null) {
            $lastNumber = SystemUnit::where('room_id', $this->room_id)
                ->where('serial_number', 'like', "{$prefix}-%")
                ->selectRaw("MAX(CAST(SUBSTRING_INDEX(serial_number, '-', -1) AS UNSIGNED)) as max_num")
                ->value('max_num');

            $startNumber = $lastNumber ? $lastNumber + 1 : 1;
        }

        $counter = $startNumber + $i;
        $candidate = sprintf('%s-%03d', $prefix, $counter);

        // Ensure uniqueness
        while (SystemUnit::where('serial_number', $candidate)->exists()) {
            $counter++;
            $candidate = sprintf('%s-%03d', $prefix, $counter);
        }

        return $candidate;
    }


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

        [$nextNumber] = $this->getNextIndex();
        $this->name = $this->category . $nextNumber;

        if (!$this->multiple) {

            $this->serial_number = $this->generateSerial();
        }
    }


    public function updatedMultiple($value)
    {
        if ($value) {
            $this->serial_number = null;
        } else {
            if ($this->category && $this->room_id) {
                [$nextNumber] = $this->getNextIndex();
                $this->serial_number = $this->generateSerial($nextNumber);
            }
        }
    }

    public function render()
    {
        return view('livewire.system-units.unit-form', [
            'rooms' => Room::all(),
        ]);
    }
}
