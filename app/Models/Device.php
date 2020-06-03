<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['device_id', 'fcm_token', 'os', 'is_checked', 'is_bulk'];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function emails()
    {
        return $this->hasMany(Email::class);
    }
}
