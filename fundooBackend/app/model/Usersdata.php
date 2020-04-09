<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Usersdata extends Model
{
    public $table = "usersdata";
    protected $fillable = [
        'name', 'email','password',
    ];
}
