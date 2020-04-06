<?php

namespace App;

use App\Models\Email;
use App\Models\Contact;
use App\Models\EmailResult;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['device_id'];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function emailResults()
    {
        return $this->hasMany(EmailResult::class, 'device_id', 'device_id');
    }
}
