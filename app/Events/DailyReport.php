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

class DailyReport implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $daily;
    
    /**
     * Create a new event instance.
     */
    public function __construct(Daily $daily)
    {
        $this->daily = $daily;
        $daily->notifications()->create([
            'type' => 'Laporan Aktifitas',
            'message' =>  ' Kamu Telah Melaporkan Kegiatan Aktivitas, Silahkan Tunggu Konfirmasi Dari Atasan! ' ,
            'message_admin' => $daily->user->name . ' Melaporkan Kegiatan Aktivitas, Silahkan Cek!',
            'user_id' => Auth::id(), 
            'read' => false
        ]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new Channel('daily-channel'); // Channel yang sama untuk semua event
    }

    public function broadcastAs()
    {
        return 'daily.report'; // Nama event yang berbeda untuk masing-masing event
    }
}
