<?php

namespace App\Events;

use App\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;


class TaskEvent extends Event implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    protected $task;

    public function __construct($task)
    {
        $this->task = $task;
    }
    public function broadcastOn()
    {
        Log::info("Broadcasting...");
        return ["task"];
    }
    public function broadcastWith()
    {
        return [
            'title' => $this->task->title
        ];
    }
    

    // public function broadcastAs()
    // {
    //     return 'my-event';
    // }
}
