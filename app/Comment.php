<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model implements HasOwner
{
    use Likeable;

    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['owner', 'likes'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['likes_count', 'is_liked'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'locked' => 'boolean'
    ];

    /**
     * Boot the comment instance.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($comment){
            $comment->post->increment('comments_count');
        });

        static::deleting(function ($comment){
            $comment->post->decrement('comments_count');
        });
    }

    /**
     * A comment has an owner.
     *
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A comment belongs to a post.
     *
     * @return BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Determine if the reply was just published a moment ago.
     *
     * @return bool
     */
    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    /**
     * Scope a query to only include inactive comments.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeActive($query)
    {
        return $query->where('locked', 0);
    }

    /**
     * Scope a query to only include active comments.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeInactive($query)
    {
        return $query->where('locked', 1);
    }
}
