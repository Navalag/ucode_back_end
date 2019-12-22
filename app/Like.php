<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Like extends Model
{
    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Boot the like instance.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($like) {
            $like->liked->owner->increment('rating');
        });

        static::deleting(function ($like) {
            $like->liked->owner->decrement('rating');
        });
    }

    /**
     * Fetch the model that was liked.
     *
     * @return MorphTo
     */
    public function liked()
    {
        return $this->morphTo();
    }

    /**
     * A like has associated user.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
