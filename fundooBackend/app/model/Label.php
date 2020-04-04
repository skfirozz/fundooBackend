<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    public $table = "label";
    protected $fillable = [
        'label', 'userid',
    ];
}
