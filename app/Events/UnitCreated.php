<?php

namespace App\Events;

use App\Models\SystemUnit;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class UnitCreated implements ShouldBroadcastNow
{
    public $unit;

    public function __construct($unit)
    {
        $this->unit = $unit;
    }

    public function broadcastOn()
    {
        return new Channel('units'); // matches echo channel
    }

    public function broadcastAs()
    {
        return 'UnitCreated';
    }
}

