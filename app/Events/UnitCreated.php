<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use App\Models\SystemUnit;


class UnitCreated implements ShouldBroadcastNow
{
    use SerializesModels;

    public $unit;

    public function __construct(SystemUnit $unit)
    {
        $this->unit = $unit;
    }

    public function broadcastOn()
    {
        return new Channel('units');
    }

    public function broadcastAs()
    {
        return 'UnitCreated';
    }
}


