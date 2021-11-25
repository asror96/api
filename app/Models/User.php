<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Laravel\Lumen\Auth\Authorizable;

class User extends Model
{
    use Authenticatable, Authorizable, HasFactory;
    protected $table='users';
    protected $fillable = [
        'name',
        'email',
        'password'
    ];


    protected $hidden = [

    ];
}
