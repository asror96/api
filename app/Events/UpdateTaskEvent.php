<?php

namespace App\Events;
use App\Models\Task;



class UpdateTaskEvent
{

    public $list_id;

    public function __construct(int $list_id)
    {
        $this->list_id=$list_id;
    }
}
