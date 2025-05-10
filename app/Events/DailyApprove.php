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

class DailyApprove
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

 
    public $daily;

    public function __construct(Daily $daily)
    {
        $this->daily = $daily;

       $daily->notifications()->create([
                'type' => 'Laporan Akrivitas Disetujui!',
                'message' =>$daily->user->name . ' Laporan Kamu Telah Diterima Admin!' ,
                'message_admin' => ' Kamu telah menerima laporan kegiatan dari' . $daily->user->name,
                'user_id' => $daily->user_id,
                'read' => false,
            ]);
        }

    public function broadcastOn()
    {
        return new Channel('daily-channel');
    }

    public function broadcastAs()
    {
        return 'daily.approve';
    }
}
