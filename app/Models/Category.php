<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function scopeTopUsed($query, $limit = 5)
    {
        return $query->withCount('posts')
            ->has('posts')
            ->orderByDesc('posts_count')
            ->limit($limit);
    }
}