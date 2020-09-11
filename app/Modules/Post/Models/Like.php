<?php

namespace Post\Models;

use Core\Models\User;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    /**
     * Properties that are mass-assignable
     */

    public $fillable = [
        'post_id',
        'user_id',
    ];

    /**
     * Date properties
     */
    public $date = [
        'deleted_at',
        'created_at'
    ];

    /**
     * Returns the related User model
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Returns the realted Post model
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
