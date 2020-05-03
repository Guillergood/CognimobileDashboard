<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model{

    protected $dates = [
        'timestamp',
    ];

    protected $fillable = [
        '_id',
        'device_id',
        'timestamp',
        'data'
    ];
}
