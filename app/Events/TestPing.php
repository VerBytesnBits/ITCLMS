<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class TestPing implements ShouldBroadcastNow
{
    public function broadcastOn()
    {
        return new Channel('test-channel');
    }

    public function broadcastAs()
    {
        return 'Pinged';
    }

    public function broadcastWith()
    {
        return ['msg' => 'Hello from Laravel!'];
    }
}
