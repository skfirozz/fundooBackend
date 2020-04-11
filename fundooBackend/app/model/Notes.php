<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    protected $fillable = [
        'title', 'description','userid','color','ispinned','isarchived','labelname','reminder',
    ];
}
