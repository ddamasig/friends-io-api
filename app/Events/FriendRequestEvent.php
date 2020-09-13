<?php

namespace App\Events;

use Core\Models\FriendRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FriendRequestEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    protected $friendRequest;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(FriendRequest $friendRequest)
    {
        $this->friendRequest = $friendRequest;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel(sprintf('user-channel.%s', $this->friendRequest->recipient->getKey()));
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'FriendRequestEvent';
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastWith()
    {
        return [
            'friend_request' => $this->friendRequest,
        ];
    }
}
