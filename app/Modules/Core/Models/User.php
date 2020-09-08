<?php

namespace Core\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Returns the related UserSocial model
     */
    public function social()
    {
        return $this->hasMany(UserSocial::class, 'user_id', 'id');
    }

    /**
     * Returns true if the user has a linked account to a specific service.
     */
    public function hasSocialLinked($service)
    {
        return (bool) $this->social()
            ->where('service', $service)
            ->count();
    }
}
