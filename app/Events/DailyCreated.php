<?php

namespace App\Events;

use App\Models\Daily;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;


use App\Models\Notification;

class DailyCreated implements ShouldBroadcastNow{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pilar;

    public function __construct(Pilar $pilar)
    {
        $this->pilar = $pilar;

        $pilar->notifications()->create([
            'type' => 'pilar',
            'message' => 'pilar baru ditambahkan: ' . $pilar->nama,
            'read' => false
        ]);
    }

    public function broadcastOn()
    {
        return new Channel('pilar-channel');
    }

    public function broadcastAs()
    {
        return 'pilar.created';
    }
    
}
