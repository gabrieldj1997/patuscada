<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PresenceJogo
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $jogoId;

    public function __construct($jogoId)
    {
        $this->jogoId = $jogoId;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('App.jogo-', $this->jogoId);
    }
}
