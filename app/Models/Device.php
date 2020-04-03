<?php

namespace App;

use App\Models\Email;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['device_id'];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function emails()
    {
        return $this->hasManyThrough(Email::class, Contact::class);
    }
}
