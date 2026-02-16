<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'category_id', 'user_id', 'published_at', 'images'];

    protected $casts = [
        'images' => 'array',
        'published_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //search and category filter section
        public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            });
        });

        $query->when($filters['category_id'] ?? null, function ($query, $category) {
            $query->where('category_id', $category);
        });
    }

   

    public function likes()
    {
        return $this->belongsToMany(User::class);
    }
}
