<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\SystemUnit;


class UnitCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $unit;

    public function __construct(SystemUnit $unit)
    {
        $this->unit = $unit->toArray();
    }

    public function broadcastOn()
    {
        return new Channel('units');
    }

    public function broadcastAs()
    {
        return 'UnitCreated';
    }
    
    public function broadcastWith()
    {
        return $this->unit;
    }
}


