<?php

namespace App\Models;

use App\Device;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['email', 'name', 'phone'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
