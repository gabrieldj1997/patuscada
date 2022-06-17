<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JogadasJogo
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $jogadorId;
    public $tp_jogada;
    public $cartas;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( $jogadorId, $tp_jogada, $cartas)
    {
        $this->jogadorId = $jogadorId;
        $this->tp_jogada = $tp_jogada;
        $this->cartas
         = $cartas;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('App.game-'.$this->jogoId);
    }

    public function broadcastAs()
    {
        return 'jogadas';
    }
}
