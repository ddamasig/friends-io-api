<?php

namespace Post\Models;

use Core\Models\Tag;
use Core\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use InteractsWithMedia;
    /**
     * Properties that are mass-assignable
     */

    public $fillable = [
        'uploader_id',
        'description',
    ];

    /**
     * Date properties
     */
    public $date = [
        'date_published',
        'deleted_at',
        'created_at'
    ];

    /**
     * Returns the related User model
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id', 'id');
    }

    /**
     * Returns the related Like model
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Returns the related Tag model
     */
    public function tags()
    {
        return $this->hasMany(Tag::class, 'post_id', 'id');
    }

    /**
     * Returns true if a Like instance exists between this Post and the user parameter
     */
    public function isLiked($user)
    {
        return $this->likes()->where('user_id', $user->getKey())
            ->exists();
    }

    /**
     * Register media collection documents
     */
    public function registerMediaCollection(): void
    {
        $this->addMediaCollection('images');
    }

    /**
     * Define media conversions
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->width(200)
            ->height(200)
            ->sharpen(10);
    }
}
