<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'google_id' => 'string',
    ];

    /**
     * Check if the user has logged in via Google.
     *
     * @return bool
     */
    public function isGoogleUser()
    {
        return !empty($this->google_id);
    }

    /**
     * Automatically set email as verified for Google users.
     */
    public function setEmailVerifiedAtAttribute($value)
    {
        if ($this->isGoogleUser() && empty($this->email_verified_at)) {
            $this->attributes['email_verified_at'] = now();
        }
    }
}
