<?php

namespace App\Listeners;

use App\Events\DeleteTaskEvent;
use App\Events\Event;
use App\Models\Task;
use App\Models\TaskList;


class DeleteTaskListener
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
    public function handle(DeleteTaskEvent $event)
    {
        $bool=true;
        $list=TaskList::find($event->list_id);
        $list->count_tasks-=1;
        $task=Task::where('list_id',$event->list_id)->pluck('is_completed');
        for($i=0;$i<count($task);$i++) {
            if ($task[$i] == false) {
                $bool=false;
            }
        }
        $list = TaskList::find($event->list_id);
        $list->is_completed = $bool;
        $list->saveQuietly();
    }
}
