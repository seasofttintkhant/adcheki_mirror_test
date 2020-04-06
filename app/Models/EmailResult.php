<?php

namespace App\Models;

use App\Device;
use Illuminate\Database\Eloquent\Model;

class EmailResult extends Model
{
    protected $fillable = ['device_id', 'result'];

    protected $casts = [
        'result' => 'array'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'device_id');
    }
}
