<?php

namespace App\Filters;

use App\User;
use Illuminate\Database\Eloquent\Builder;

class PostFilters extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['by', 'popular', 'unanswered'];

    /**
     * Filter the query by a given username.
     *
     * @param  string $username
     * @return Builder
     */
    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    /**
     * Filter the query according to most popular posts.
     *
     * @return Builder
     */
    protected function popular()
    {
        $this->builder->getQuery()->orders = [];

        return $this->builder->orderBy('comments_count', 'desc');
    }

    /**
     * Filter the query according to those that are unanswered.
     *
     * @return Builder
     */
    protected function unanswered()
    {
        return $this->builder->where('comments_count', 0);
    }
}
