<?php

namespace App\Services\User;

use App\Exceptions\UserNotFoundException;
use App\Models\Post;
use App\Models\User;
use App\Models\UserActivity;

class UserService
{
    public function index(array $filters)
    {
        return User::query()
            ->when($filters['status'] ?? null, fn($query, string $status) => $query->where('status', $status)
            )
            ->when($filters['search'] ?? null, function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->paginate(
                perPage: $filters['pageSize'] ?? 25,
                page: $filters['page'] ?? 1
            );
    }

    public function show(int $userId)
    {
        $user = User::whereId($userId)->first();

        if (!$user) {
            throw new UserNotFoundException;
        }

        return $user;
    }

    public function getUserPosts(int $userId, array $paginationOptions)
    {
        return Post::query()
            ->where([
                'user_id' => $userId,
            ])
            ->paginate(
                perPage: $paginationOptions['pageSize'] ?? 25,
                page: $paginationOptions['page'] ?? 1
            );
    }

    public function getUserActivity(int $userId)
    {
        return UserActivity::getDailyActivityByUserId($userId);
    }
}
