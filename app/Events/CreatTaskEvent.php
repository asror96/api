<?php

namespace App\Events;
use App\Models\Task;



class CreatTaskEvent
{
    public $task;
    public $list_id;

    public function __construct(int $task)
    {
        $this->task=$task;
    }
}
