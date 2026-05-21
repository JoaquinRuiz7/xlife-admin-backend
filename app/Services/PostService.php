<?php

namespace App\Services;

use App\Models\Post;

class PostService
{
    public function getPosts(array $filters)
    {
        return Post::query()
            ->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('status', $status)
            )
            ->when($filters['search'] ?? null, function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('content', 'like', "%{$search}%");
                });
            })
            ->paginate(
                perPage: $filters['pageSize'] ?? 25,
                page: $filters['page'] ?? 1
            );
    }
}
