<?php

namespace App;

use App\Filters\PostFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Post extends Model implements HasOwner
{
    use Likeable;

    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The relationships to always eager-load.
     *
     * @var array
     */
    protected $with = ['owner', 'categories', 'likes'];

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
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {
            $post->comments->each->delete();
        });
    }

    /**
     * A post belongs to a owner.
     *
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A post may have many comments.
     *
     * @return HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)
            ->withCount('likes')
            ->with('owner');
    }

    /**
     * A post may have many categories.
     *
     * @return BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Add a comment to the post.
     *
     * @param  array $comment
     *
     * @return Model
     */
    public function addComment($comment)
    {
        $comment = $this->comments()->create($comment);

        return $comment;
    }

    /**
     * Apply all relevant post filters.
     *
     * @param  Builder     $query
     * @param  PostFilters $filters
     *
     * @return Builder
     */
    public function scopeFilter($query, PostFilters $filters)
    {
        return $filters->apply($query);
    }

    /**
     * Scope a query to only include inactive posts.
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
     * Scope a query to only include active posts.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeInactive($query)
    {
        return $query->where('locked', 1);
    }

    /**
     * Set the proper slug attribute.
     *
     * @param string $value
     */
    public function setSlugAttribute($value)
    {
        $slug = Str::kebab($value);
        $original = $slug;
        $count = 2;

        while (static::whereSlug($slug)->exists()) {
            $slug = "{$original}-" . $count++;
        }

        $this->attributes['slug'] = $slug;
    }
}
