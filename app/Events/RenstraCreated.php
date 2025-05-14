<?php
namespace App\Events;

use App\Models\Notification;
use App\Models\Renstra;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class RenstraCreated implements ShouldBroadcastNow{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $renstra;

    public function __construct(Renstra $renstra)
    {
        $this->renstra = $renstra;

        $renstra->notifications()->create([
            'type' => 'renstra',
            'message' => 'Renstra baru ditambahkan: ' . $renstra->nama,
            'user_id' => Auth::id(), 
            'read' => false
        ]);
    }

    public function broadcastOn()
    {
        return new Channel('renstra-channel');
    }

    public function broadcastAs()
    {
        return 'renstra.created';
    }
}
