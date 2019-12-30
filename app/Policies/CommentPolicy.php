<?php

namespace App\Policies;

use App\Comment;
use App\Post;
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

    public function delete(User $user, Comment $comment){
        return $user->id === $comment->user_id;
    }

    public function store(User $user, Post $post){
        return $user->id === $post->user_id || in_array($post->user_id, $user
                ->following()
                ->where(['accepted'=>1])
                ->pluck('to_user_id')
                ->toArray());
    }
}
