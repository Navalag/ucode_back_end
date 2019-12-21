<?php

namespace App\Policies;

use App\Comment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the authenticated user has permission to create a new comment.
     *
     * @param  User $user
     * @return bool
     */
    public function create(User $user)
    {
        if (! $lastReply = $user->fresh()->lastReply) {
            return true;
        }

        return ! $lastReply->wasJustPublished();
    }

    /**
     * Determine if the authenticated user has permission to update a comment.
     *
     * @param  User    $user
     * @param  Comment $comment
     *
     * @return bool
     */
    public function update(User $user, Comment $comment)
    {
        return $comment->user_id == $user->id;
    }

    public function destroy(User $user, Comment $comment)
    {
        if ($user->role->name == 'admin') {
            return true;
        }

        return $comment->user_id == $user->id;
    }
}
