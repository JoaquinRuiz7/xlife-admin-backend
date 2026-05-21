<?php

namespace App\Services;

use App\Models\PostComment;

class CommentService
{
    public function getComments(array $filters)
    {
        return PostComment::query()
            ->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('status', $status)
            )
            ->when($filters['search'] ?? null, function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('comment', 'like', "%{$search}%");
                });
            })
            ->paginate(
                perPage: $filters['pageSize'] ?? 25,
                page: $filters['page'] ?? 1
            );
    }
}
