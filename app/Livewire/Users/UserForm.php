<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use App\Models\Room;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class UserForm extends Component
{
    public ?User $user = null;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public array $roles = [];             // All available roles (name => label)
    public array $selectedRoles = [];     // Selected roles via checkboxes

    public $assigned_room_id = null;
    public array $roomOptions = [];

    public function mount($userId = null)
    {
        $this->roomOptions = Room::pluck('name', 'id')->toArray();

        $this->roles = Role::where('guard_name', 'web')
            ->pluck('name')
            ->mapWithKeys(fn($name) => [$name => ucwords(str_replace('_', ' ', $name))])
            ->toArray();

        if ($userId) {
            $this->user = User::findOrFail($userId);
            $this->name = $this->user->name ?? '';
            $this->email = $this->user->email ?? '';
            $this->selectedRoles = $this->user->roles()->pluck('name')->toArray();
            $this->assigned_room_id = $this->user->assigned_room_id ?? null;
        }
        if (!$userId) {
            $this->selectedRoles = ['lab_technician']; // ✅ default roles
        }
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user?->id)],
            'password' => [$this->user ? 'nullable' : 'required', 'min:6'],
            'selectedRoles' => ['required', 'array', 'min:1'],
            'selectedRoles.*' => ['string', Rule::in(array_keys($this->roles))],
            'assigned_room_id' => ['nullable', 'exists:rooms,id'],
        ];
    }

    public function save()
    {
        try {
            $validated = $this->validate();

            // Prevent assigning a room unless user has Lab Incharge role

            if ($this->assigned_room_id && !in_array('lab_incharge', $this->selectedRoles)) {
                throw ValidationException::withMessages([
                    'assigned_room_id' => 'Only users with the Lab Incharge role can be assigned to a room.',
                ]);
            }

            // Prevent assigning room if user is Chairman (they’re auto-assigned to all)
            if (in_array('chairman', $this->selectedRoles)) {
                $this->assigned_room_id = null;
            }

            // 🧹 Handle previous lab in-charge reassignment
            if ($this->assigned_room_id) {
                $previousUser = User::where('assigned_room_id', $this->assigned_room_id)
                    ->when($this->user, fn($q) => $q->where('id', '!=', $this->user->id))
                    ->first();

                if ($previousUser) {
                    $previousUser->update(['assigned_room_id' => null]);
                }

                $previousRoom = Room::find($this->assigned_room_id);
                if ($previousRoom && $previousRoom->lab_in_charge_id && $previousRoom->lab_in_charge_id !== optional($this->user)->id) {
                    User::where('id', $previousRoom->lab_in_charge_id)
                        ->update(['assigned_room_id' => null]);
                }

                Room::where('id', $this->assigned_room_id)
                    ->update(['lab_in_charge_id' => optional($this->user)->id]);
            }

            if ($this->user) {
                $updateData = [
                    'name' => $this->name,
                    'email' => $this->email,
                    'assigned_room_id' => $this->assigned_room_id,
                ];

                if (!empty($this->password)) {
                    $updateData['password'] = bcrypt($this->password);
                }

                $this->user->update($updateData);
                $this->user->syncRoles($this->selectedRoles);

                Room::where('lab_in_charge_id', $this->user->id)
                    ->where('id', '!=', $this->assigned_room_id)
                    ->update(['lab_in_charge_id' => null]);

                $this->dispatch('swal', toast: true, icon: 'success', title: 'User updated successfully', timer: 3000);
                $this->dispatch('userUpdated');
            } else {
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => bcrypt($this->password),
                    'assigned_room_id' => $this->assigned_room_id,
                ]);


                $user->assignRole($this->selectedRoles);

                if ($this->assigned_room_id) {
                    Room::where('id', $this->assigned_room_id)
                        ->update(['lab_in_charge_id' => $user->id]);
                }

                $this->dispatch('swal', toast: true, icon: 'success', title: 'User created successfully', timer: 3000);
                $this->dispatch('userCreated');
            }

            $this->dispatch('closeModal');
        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
            $errors = $e->validator->errors()->all();
            $this->dispatch('swal', toast: true, icon: 'error', title: implode(' ', $errors), timer: 3000);
        }
    }

    public function render()
    {
        return view('livewire.users.user-form');
    }
}
