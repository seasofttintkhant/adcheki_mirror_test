<?php

namespace App\Models;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = ['email'];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    protected $casts = [
        'status' => 'boolean'
    ];
}
