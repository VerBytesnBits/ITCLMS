<?php

namespace App\Events;

use App\Models\SystemUnit;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UnitCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $unit;

    public function __construct(SystemUnit $unit)
    {
        $this->unit = $unit->load('room'); // eager load room if needed
    }

    public function broadcastOn()
    {
        return new Channel('units'); // all listeners on "units" channel will hear this
    }

    public function broadcastAs()
    {
        return 'UnitCreated'; // event name
    }
 
}
