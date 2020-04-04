<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Labels extends Model
{
    protected $fillable = [
        'label', 'userid',
    ];
}
