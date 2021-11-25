<?php

namespace App\Events;



class CreatListEvent
{
    public $list_id;
    public function __construct(int $list_id)
    {
        $this->list_id=$list_id;
    }
}
