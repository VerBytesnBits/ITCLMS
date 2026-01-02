<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserService
{
    /**
     * Create user
     */
    public function saveUser(array $userRequest): User
    {
        $user = User::create([
            'name'     => $userRequest['name'],
            'email'    => $userRequest['email'],
            'password' => Hash::make($userRequest['password']),
        ]);

        if (!empty($userRequest['role'])) {
            $user->assignRole($userRequest['role']);
        }

        return $user;
    }

    /**
     * Get user by ID
     */
    public function getUserById(int $userId): ?User
    {
        return $this->getAllUsers()->find($userId);
    }

    /**
     * Get users query
     */
    public function getAllUsers()
    {
        return User::with('roles');
    }

    /**
     * Update user
     */
    public function updateUser(int $userId, array $userRequest): bool
    {
        $user = $this->getAllUsers()->find($userId);

        if (!$user) {
            return false;
        }

        $user->name  = $userRequest['name'];
        $user->email = $userRequest['email'];

        if (!empty($userRequest['password'])) {
            $user->password = Hash::make($userRequest['password']);
        }

        $user->save();

        if (!empty($userRequest['role'])) {
            $user->syncRoles([$userRequest['role']]);
        }

        return true;
    }

    /**
     * Delete user
     */
    public function deleteUser(int $userId): bool
    {
        $user = $this->getAllUsers()->find($userId);

        if (!$user) {
            return false;
        }

        return (bool) $user->delete();
    }

    /**
     * Get available roles
     */
    public function getRoles(): array
    {
        return Role::where('guard_name', 'web')
            ->pluck('name')
            ->mapWithKeys(fn ($name) => [$name => ucwords(str_replace('_', ' ', $name))])
            ->toArray();
    }
}
