<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class RoleColorService
{
    protected static array $roleColors = [
        'chairman' => 'bg-red-100 text-red-800',
        'lab_incharge' => 'bg-yellow-100 text-yellow-800',
        'lab_technician' => 'bg-green-100 text-green-800',
    ];

    protected static array $availableColors = [
        'bg-blue-100 text-blue-800',
        'bg-indigo-100 text-indigo-800',
        'bg-purple-100 text-purple-800',
        'bg-pink-100 text-pink-800',
        'bg-teal-100 text-teal-800',
        'bg-orange-100 text-orange-800',
        'bg-cyan-100 text-cyan-800',
        'bg-lime-100 text-lime-800',
        'bg-amber-100 text-amber-800',
        'bg-fuchsia-100 text-fuchsia-800',
    ];

    public static function get(string $roleName): string
    {
        // Return predefined color if role is fixed
        if (isset(self::$roleColors[$roleName])) {
            return self::$roleColors[$roleName];
        }

        // Else use session-based dynamic assignment
        $sessionRoleColors = Session::get('role_colors', []);

        if (!isset($sessionRoleColors[$roleName])) {
            $usedColors = array_values($sessionRoleColors);
            $unusedColors = array_diff(self::$availableColors, $usedColors);

            $color = collect($unusedColors)->random() ?? 'bg-gray-100 text-gray-800';
            $sessionRoleColors[$roleName] = $color;

            Session::put('role_colors', $sessionRoleColors);
        }

        return $sessionRoleColors[$roleName];
    }

    public static function forget(string $roleName): void
    {
        if (isset(self::$roleColors[$roleName])) {
            return; // Do not remove fixed roles
        }

        $sessionRoleColors = Session::get('role_colors', []);
        unset($sessionRoleColors[$roleName]);
        Session::put('role_colors', $sessionRoleColors);
    }

    public static function reset(): void
    {
        Session::forget('role_colors');
    }
}

