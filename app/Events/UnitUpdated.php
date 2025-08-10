<?php

namespace App\Events;

use App\Models\SystemUnit;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UnitUpdated implements ShouldBroadcast
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
        $unit->load('room');
        $this->unit = $unit->toArray();
    }

    public function broadcastOn()
    {
        return new Channel('units');
    }

    public function broadcastAs()
    {
        return 'UnitUpdated';
    }

    public function broadcastWith()
    {
        return $this->unit;
    }
}
