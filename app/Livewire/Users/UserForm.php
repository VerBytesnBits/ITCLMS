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

    public array $roles = [];         // All available roles
    public ?string $selectedRole = null; // Single role

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
            $this->selectedRole = $this->user->roles()->first()?->name;
            $this->assigned_room_id = $this->user->assigned_room_id ?? null;
        }

        if (!$userId) {
            $this->selectedRole = 'lab_technician'; // default role
        }
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user?->id)],
            'password' => [$this->user ? 'nullable' : 'required', 'min:6'],
            'selectedRole' => ['required', 'string', Rule::in(array_keys($this->roles))],
            'assigned_room_id' => ['nullable', 'exists:rooms,id'],
        ];
    }

    public function save()
    {
        try {
            $validated = $this->validate();

            // Only Lab Incharge can be assigned a room
            if ($this->assigned_room_id && $this->selectedRole !== 'lab_incharge') {
                throw ValidationException::withMessages([
                    'assigned_room_id' => 'Only users with the Lab Incharge role can be assigned to a room.',
                ]);
            }

            // Chairmen cannot have a specific room
            if ($this->selectedRole === 'chairman') {
                $this->assigned_room_id = null;
            }

            // Handle previous lab in-charge reassignment
            if ($this->assigned_room_id) {
                $previousUser = User::where('assigned_room_id', $this->assigned_room_id)
                    ->when($this->user, fn($q) => $q->where('id', '!=', $this->user->id))
                    ->first();

                if ($previousUser) {
                    $previousUser->update(['assigned_room_id' => null]);
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
                $this->user->syncRoles([$this->selectedRole]);

                $this->dispatch('swal', toast: true, icon: 'success', title: 'User updated successfully', timer: 3000);
                $this->dispatch('userUpdated');
            } else {
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => bcrypt($this->password),
                    'assigned_room_id' => $this->assigned_room_id,
                ]);

                $user->assignRole([$this->selectedRole]);

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
