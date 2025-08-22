<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UnitDeleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $unitId;

    /**
     * Create a new event instance.
     *
     * @param array|int $unitData
     */
    public function __construct($unitData)
    {
        // The constructor expects an array with 'id' or directly the id
        $this->unitId = is_array($unitData) ? $unitData['id'] : $unitData;
    }

    public function broadcastOn()
    {
        return new Channel('units');
    }

    public function broadcastAs()
    {
        return 'UnitDeleted';
    }

    public function broadcastWith()
    {
        return ['id' => $this->unitId];
    }
}
