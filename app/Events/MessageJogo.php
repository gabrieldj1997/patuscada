<?php

namespace App\Events;

use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageJogo
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $jogoId;
    public $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( $jogoId, $data)
    {
        $this->jogoId = $jogoId;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('game-'.$this->jogoId);
    }

    public function broadcastAs()
    {
        return 'message';
    }
}
