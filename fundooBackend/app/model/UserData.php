<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    public $table = "usersdata";
    protected $fillable = [
        'name', 'email','password',
    ];
}
