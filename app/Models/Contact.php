<?php

namespace App\Models;

use App\Device;
use App\Models\Email;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['data'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function emails()
    {
        return $this->hasMany(Email::class);
    }
}
