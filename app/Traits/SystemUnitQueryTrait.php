<?php

namespace App\Traits;

use App\Models\SystemUnit;
use Illuminate\Database\Eloquent\Builder;

trait SystemUnitQueryTrait
{
    protected function baseUnitsQuery(): Builder
    {
        $user = auth()->user();

        $query = SystemUnit::query()
            ->with(['room', 'components', 'peripherals']);

        if (!$user->hasAnyRole(['chairman', 'Tester'])) {

            $userRoles = $user->roles->pluck('name');

            $roomIds = $user->rooms()
                ->whereIn('role_in_room', $userRoles)
                ->pluck('rooms.id');

            $query->whereIn('room_id', $roomIds);
        }

        return $query;
    }
  
}