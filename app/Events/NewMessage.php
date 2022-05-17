<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;//SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $users;
    public $message;
    public function __construct($users, $message)
    {
       
        $this->message = $message;
        $this->sender = $users;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel("chat.4");
        //var_dump($this->message['to_id']);die;
        return new PrivateChannel("chat.".$this->message['to_id']);
    }
}
