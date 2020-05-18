<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $guard = 'admin';

    const INITIAL_ID = 1000000000;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'operator_id', 'login_id', 'password', 'role', 'permitted_ip',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public function username()
    {
        return 'login_id';
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($operator) {
            $operator->operator_id = self::INITIAL_ID + $operator->id;
            $operator->save();
        });
    }

    public function isSuperAdmin()
    {
        return Auth::guard('admin')->check() && Auth::guard('admin')->user()->role == 1;
    }
}
