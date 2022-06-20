<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JogadasJogo implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $jogoId;
    public $jogadorId;
    public $tp_jogada;
    public $cartas;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( $jogoId ,$jogadorId, $tp_jogada, $cartas)
    {
        $this->jogoId = $jogoId;
        $this->jogadorId = $jogadorId;
        $this->tp_jogada = $tp_jogada;
        $this->cartas = $cartas;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('jogo-jogada-'.$this->jogoId);
    }

    public function broadcastAs()
    {
        return 'jogadas';
    }
}
