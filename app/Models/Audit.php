<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $fillable = [
        'device_id',
        'os',
        'total_email_received',
        'email_received_date',
        'result_pushed_date'
    ];

    protected $dates = ['email_received_date', 'result_pushed_date'];
}
