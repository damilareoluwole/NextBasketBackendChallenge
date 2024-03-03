<?php

namespace App\Events;

use App\Contract\Interface\EventInterface;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserCreated implements EventInterface
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The event name.
     *
     * @var string
     */
    public $event;

    /** The newly created user */
    public $user;
    /**
     * Create a new event instance.
     * 
     * @var User
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->eventName();
    }

    /** Set the event name */
    public function eventName(): void
    {
        $this->event = "user-created";
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
