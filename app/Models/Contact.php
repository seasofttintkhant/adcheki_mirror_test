<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['data'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
