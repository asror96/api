<?php

namespace App\Providers;

use App\Events\UpdateTaskEvent;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\CreatTaskEvent::class => [
            \App\Listeners\CountTaskListener::class,
        ],
        \App\Events\UpdateTaskEvent::class=>[
            \App\Listeners\UpdateTaskListener::class,
        ],
        \App\Events\DeleteTaskEvent::class => [
            \App\Listeners\DeleteTaskListener::class,
        ],
        \App\Events\CreatListEvent::class => [
            \App\Listeners\CreateListListener::class,
        ],
        \App\Events\DeleteListEvent::class => [
            \App\Listeners\DeleteTaskListener::class,
        ],
    ];
}
