<?php

namespace App\Events;

use App\Models\SystemUnit;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UnitUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $unit;

    /**
     * Create a new event instance.
     *
     *  $unit
     */
    public function __construct( $unit)
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
