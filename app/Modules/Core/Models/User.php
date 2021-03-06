<?php

namespace Core\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

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
     * Returns the related Friend model
     */
    public function friends()
    {
        return $this->hasMany(Friend::class, 'owner_id', 'id');
    }

    /**
     * Returns a collection of related FriendRequest model
     */
    public function friendRequests()
    {
        return $this->hasMany(FriendRequest::class);
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

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
