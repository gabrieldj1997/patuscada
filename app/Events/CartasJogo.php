<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CartasJogo implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $jogoId;
    public $jogadorId;
    public $cartas;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( $jogoId, $jogadorId, $cartas)
    {
        $this->jogoId = $jogoId;
        $this->jogadorId = $jogadorId;
        $this->cartas = $cartas;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new  Channel('game-cartas'.$this->jogoId);
    }

    public function broadcastAs()
    {
        return 'cartas-'.$this->jogadorId;
    }
}
