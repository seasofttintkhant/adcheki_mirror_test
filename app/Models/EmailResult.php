<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailResult extends Model
{
    protected $fillable = ['device_id', 'result'];

    protected $casts = [
        'result' => 'array'
    ];

}
