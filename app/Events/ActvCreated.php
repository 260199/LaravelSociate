<?php

namespace App\Events;

use App\Models\Daily;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class ActvCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $daily;

    public function __construct(Daily $daily)
    {
        $this->daily = $daily;

        $daily->notifications()->create([
            'type' => 'daily',
            'message' => 'daily baru ditambahkan: ' . $daily->nama,
            'user_id' => Auth::id(), 
            'read' => false
        ]);
    }

    public function broadcastOn()
    {
        return new Channel('daily-channel');
    }

    public function broadcastAs()
    {
        return 'daily.created';
    }
}
