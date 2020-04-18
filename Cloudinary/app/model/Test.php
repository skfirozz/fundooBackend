<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table='test';
    protected $fillable = [
        'token', 'about', 
    ];
}
