<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Eloquent extends Model
{
    protected $table='eloquent';
    protected $fillable = [
        'title', 'description', 
    ];
}
