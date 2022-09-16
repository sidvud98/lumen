<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    // protected $listen = [
    //     \App\Events\ExampleEvent::class => [
    //         \App\Listeners\ExampleListener::class,
    //     ],
    // ];
    protected $listen = [
        \App\Events\TaskEvent::class => [
            \App\Listeners\TaskListener::class,
        ],
    ];

}
