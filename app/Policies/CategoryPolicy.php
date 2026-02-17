<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Category $category): bool
    {
        // Admins can view any category
        if ($user->isAdmin()) {
            return true;
        }
        
        return $user->id === $category->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Category $category): bool
    {
        // Admins can update any category
        if ($user->isAdmin()) {
            return true;
        }
        
        return $user->id === $category->user_id;
    }

    public function delete(User $user, Category $category): bool
    {
        // Admins can delete any category
        if ($user->isAdmin()) {
            return true;
        }
        
        return $user->id === $category->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Category $category): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Category $category): bool
    {
        return false;
    }
}
