<?php

namespace App\Listeners;

use App\Events\TaskEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\Mail;
class TaskListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        

    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ExampleEvent  $event
     * @return void
     */
    public function handle(TaskEvent $event)
    {
        //
        Mail::raw('you have new task', function ($message)  {
            $message->to('xyz@gmail.com', 'fdgchv')->subject
               ('new task assigned');
            $message->from('xyz@gmail.com','react-app');
        });
    }
}
