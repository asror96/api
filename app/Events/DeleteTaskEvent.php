<?php

namespace App\Events;
use App\Models\Task;



class DeleteTaskEvent
{

    public $list_id;

    public function __construct(int $list_id)
    {
        $this->list_id=$list_id;
    }
}
