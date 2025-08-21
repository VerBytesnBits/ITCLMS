<?php

namespace App\Events;

use App\Models\SystemUnit;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UnitUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $unit;

    public function __construct(SystemUnit $unit)
    {
        $this->unit = $unit;
    }

    public function broadcastOn()
    {
        return new Channel('units');
    }

    public function broadcastWith()
    {
        return [
            'unit' => $this->unit->toArray(),
        ];
    }
}
