<?php

namespace App\Events;

use App\Models\Daily;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class DailyUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $daily;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public function __construct(Daily $daily)
    {
        $this->daily = $daily;

        $daily->notifications()->create([
            'type' => 'Inputan Aktifitas',
            'message' => $daily->user->name .' Telah Melakukan Upload Data!! ',
            'user_id' => Auth::id(), 
            'read' => false
        ]);
    }
    public function broadcastOn()
    {
        return new Channel('daily-channel'); // 1 Channel untuk bersama ( create, update, delete )
    }

    public function broadcastAs()
    {
        return 'daily.updated'; // nah ini untuk pembeda aksi untuk channel broadcast on kita 
    }
}

