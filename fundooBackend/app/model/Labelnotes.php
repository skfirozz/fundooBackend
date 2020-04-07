<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Labelnotes extends Model
{
    protected $fillable = [
        'labelname', 'userid','noteid',
    ];
}
