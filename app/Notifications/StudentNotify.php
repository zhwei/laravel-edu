<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class StudentNotify extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    private $content;
    /**
     * @var string
     */
    private $time;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $content)
    {
        $this->content = $content;
        $this->time = Carbon::now()->toDateTimeString();
    }

    public function via()
    {
        return ['broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'time' => $this->time,
            'content' => $this->content,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    public function broadcastType()
    {
        return 'students';
    }
}
