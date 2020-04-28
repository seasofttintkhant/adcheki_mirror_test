<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    const INITIAL_ID = 1000000000;

    protected $fillable = [
        'mail_address_id',
        'contact_id',
        'email',
        'is_valid',
        'status'
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($email) {
            $email->mail_address_id = self::INITIAL_ID + $email->id;
            $email->save();
        });
    }
}
