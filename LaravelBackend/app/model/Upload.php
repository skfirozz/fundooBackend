<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    public $table = "uploads";
    protected $fillable = [
        'image_name', 'image_url',
    ];
}
