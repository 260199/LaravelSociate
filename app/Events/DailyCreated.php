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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DailyCreated implements ShouldBroadcastNow{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $daily;

    public function __construct(Daily $daily)
    {
        $this->daily = $daily;
        // Menambahkan notifikasi pada Daily
        $daily->notifications()->create([
            'type' => 'Inputan Aktifitas',
            'message' => 'Aktifitas baru ' . $daily->user->name,
            'message_admin' => 'Aktifitas Baru Telah Kamu Tambahkan',
            'user_id' => Auth::id(),
            'read' => false
        ]);
    
        // Log untuk memastikan event dikirim
        Log::info('Event DailyCreated dipancarkan untuk ID: ' . Auth::id());
    }
    

    public function broadcastOn()
    {
        return new PrivateChannel('admin.' . Auth::id()); // Broadcast hanya ke admin dengan id yang sesuai
    }

    public function broadcastAs()
    {
        return 'daily.created'; // Nama event yang berbeda untuk masing-masing event
    }
}