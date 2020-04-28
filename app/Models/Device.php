<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['device_id', 'os', 'is_checked'];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function emailResults()
    {
        return $this->hasMany(EmailResult::class, 'device_id', 'device_id');
    }

    public function emails()
    {
        return $this->hasManyThrough(Email::class, Contact::class);
    }
}
