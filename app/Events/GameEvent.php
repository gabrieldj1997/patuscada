<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $nickname;
    public $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($nickname, $data)
    {
        $this->nickname = $nickname;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('game-'.$this->data->game->id);
    }

    public function broadcastAs()
    {
        return 'message';
    }
}
