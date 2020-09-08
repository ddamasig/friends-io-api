<?php

namespace Library\Models;

use Core\Models\User;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    /**
     * Properties that are mass-assignable
     */
    public $fillable = [
        'parent_id',
        'uploader_id',
        'title',
        'description',
        'rating',
        'date_published'
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
     * Returns the realted Material model
     */
    public function parent()
    {
        return $this->belongsTo(Material::class, 'parent_id', 'id');
    }
}
