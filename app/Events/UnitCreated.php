<?php

namespace App\Events;

use App\Models\SystemUnit;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UnitCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $unit;

    /**
     * Create a new event instance.
     *
     * @param SystemUnit $unit
     */
    public function __construct(SystemUnit $unit)
    {
        // Make sure the unit is loaded with all needed relations for frontend
        $unit->load('room');

        // Convert to array for broadcasting
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
