<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Laravel\Lumen\Auth\Authorizable;

class Task extends Model
{
    use Authenticatable, Authorizable, HasFactory;
    protected $table='tasks';
    protected $fillable = [
        'name',
        'list_id',
        'executor_user_id',
        'is_completed',
        'description',
        'urgency'
    ];


    protected $hidden = [

    ];

    public function user()
    {
        $this->belongsTo(
            User::class,
            'executor_user_id'
        );
    }

    public function list()
    {
        $this->belongsTo(
            TaskList::class,
            'list_id'
        );
    }
}
