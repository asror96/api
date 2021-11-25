<?php

namespace App\Listeners;

use App\Events\CreatTaskEvent;
use App\Events\Event;
use App\Models\TaskList;


class CountTaskListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Event  $event
     * @return void
     */
    public function handle(CreatTaskEvent $event)
    {
        $task=TaskList::find($event->task);
        $task->count_tasks+=1;
        $task->saveQuietly();
    }
}
