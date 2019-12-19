<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Post extends Model
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
    protected $with = ['creator', 'category', 'likes'];

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
     * A post belongs to a creator.
     *
     * @return BelongsTo
     */
    public function creator()
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
//            ->withCount('favorites')
            ->with('owner');
    }

    /**
     * A post is assigned to a category.
     *
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
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
