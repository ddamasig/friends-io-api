<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    const PENDING = 'pending';
    const ACCEPTED = 'accepted';
    const REJECTED = 'rejected';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'owner_id',
        'user_id'
    ];

    /**
     * Returns the related User model
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    /**
     * Returns the related User model
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
