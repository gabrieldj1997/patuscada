<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HostJogo 
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
        return new PrivateChannel('App.host-game-'.$this->jogoId);
    }

    public function broadcastAs()
    {
        return 'host';
    }
}
