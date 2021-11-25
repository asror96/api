<?php
namespace App\Listeners;
use App\Models\Task;

use App\Events\CreatTaskEvent;
use App\Events\Event;
use App\Events\UpdateTaskEvent;
use App\Models\TaskList;
use phpDocumentor\Reflection\Types\Boolean;

class UpdateTaskListener
{
    public function __construct()
    {
        //
    }

    public function handle(UpdateTaskEvent $event)
    {
        $bool=true;
        $task=Task::where('list_id',$event->list_id)->pluck('is_completed');
        for($i=0;$i<count($task);$i++) {
            if ($task[$i] == false) {
                $bool=false;
            }
        }
        $list = TaskList::find($event->list_id);
        $list->is_completed = $bool;
        $list->save();
        dd($list);
    }
}
