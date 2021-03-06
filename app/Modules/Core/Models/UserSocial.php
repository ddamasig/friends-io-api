<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;

class UserSocial extends Model
{
    protected $table = 'user_social';

    protected $fillable = [
        'user_id',
        'social_id',
        'service',
        'token'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}