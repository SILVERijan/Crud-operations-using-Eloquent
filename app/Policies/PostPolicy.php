<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine if the user can view any posts.
     * All authenticated users can view the post list.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view posts
    }

    /**
     * Determine if the user can view a specific post.
     * All authenticated users can view individual posts.
     */
    public function view(User $user, Post $post): bool
    {
        return true; // All authenticated users can view any post
    }

    /**
     * Determine if the user can create posts.
     * Only customers and admins can create posts (readers cannot).
     */
    public function create(User $user): bool
    {
        return $user->isCustomer() || $user->isAdmin();
    }

    /**
     * Determine if the user can update the post.
     * Only the post owner or admins can update.
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can delete the post.
     * Only the post owner or admins can delete.
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can restore the post.
     */
    public function restore(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can permanently delete the post.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return $user->isAdmin();
    }
}
