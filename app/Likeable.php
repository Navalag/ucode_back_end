<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Likeable
{
    /**
     * Boot the trait.
     */
    protected static function bootLikeable()
    {
        static::deleting(function ($model) {
            $model->likes->each->delete();
        });
    }

    /**
     * A model can be liked.
     *
     * @return MorphMany
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'liked');
    }

    /**
     * Determine if the current model has been liked.
     *
     * @return boolean
     */
    public function isLiked()
    {
        return !! $this->likes->where('user_id', auth()->id())->count();
    }

    /**
     * Fetch the liked status as a property.
     *
     * @return bool
     */
    public function getIsLikedAttribute()
    {
        return $this->isLiked();
    }

    /**
     * Get the number of likes for the comment.
     *
     * @return integer
     */
    public function getLikesCountAttribute()
    {
        return $this->likes->count();
    }

    /**
     * Like the current model instance.
     *
     * @return Model
     */
    public function like()
    {
        $attributes = ['user_id' => auth()->id()];

        if (! $this->likes()->where($attributes)->exists()) {
            return $this->likes()->create($attributes);
        }
    }

    /**
     * Unlike the current model.
     */
    public function unlike()
    {
        $attributes = ['user_id' => auth()->id()];

        $this->likes()->where($attributes)->get()->each->delete();
    }
}
