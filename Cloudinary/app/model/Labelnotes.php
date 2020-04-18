<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Labelnotes extends Model
{
    protected $table='labelnotes';
    protected $fillable = [
        'labelname', 'userid','noteid',
    ];
}
