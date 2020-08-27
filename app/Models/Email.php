<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    const INITIAL_ID = 1000000000;

    protected $fillable = [
        'mail_address_id',
        'device_id',
        'email',
        'is_valid',
        'status',
        'ok',
        'ng',
        'unknown'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($email) {
            $email->mail_address_id = $email->id > self::INITIAL_ID ? $email->id : self::INITIAL_ID + $email->id;
            $email->save();
        });
    }
}
