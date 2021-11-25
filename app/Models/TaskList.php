<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Laravel\Lumen\Auth\Authorizable;

class TaskList extends Model
{
    use Authenticatable, Authorizable, HasFactory;
    protected $table='lists';
    protected $fillable = [
        'name',
        'count_tasks',
        'is_completed',
        'is_closed',
        'created_at',
        'updated_at'
    ];


    protected $hidden = [

    ];
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'user_lists',
            'list_id',
            'user_id'
        );
    }
    public function tasks()
    {
        return  $this->hasMany
        (
            Task::class,
            'list_id'
        );
    }
}
