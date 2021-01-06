<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ChatEvent implements ShouldBroadcast
{
    /**
     * @var string
     */
    private $channel;

    /**
     * @var string
     */
    private $content;

    /**
     * @var User
     */
    private $sender;

    public function __construct(int $teacherId, int $studentId, string $content, User $sender)
    {
        $this->channel = "room.{$studentId}.{$teacherId}";
        $this->content = $content;
        $this->sender = $sender;
    }

    public function broadcastOn()
    {
        return new PrivateChannel($this->channel);
    }

    public function broadcastAs()
    {
        return 'chat';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->sender->id,
            'name' => $this->sender->name,
            'content' => $this->content,
        ];
    }
}
