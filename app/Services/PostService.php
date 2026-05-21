<?php

namespace App\Services;

use App\Exceptions\PostNotFoundException;
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

    public function getPostComments(int $postId, array $pageInfo)
    {
        $post = Post::whereId($postId)->first();
        if (! $post) {
            throw new PostNotFoundException;
        }

        return $post
            ->comments()
            ->paginate(
                perPage: $pageInfo['pageSize'] ?? 25,
                page: $pageInfo['page'] ?? 1
            );
    }
}
