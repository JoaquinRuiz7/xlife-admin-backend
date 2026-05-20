<?php

namespace App\Services\User;

use App\Exceptions\UserNotFoundException;
use App\Models\User;

class UserService
{
    public function index(array $filters)
    {
        return User::query()
            ->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('status', $status)
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

        if (! $user) {
            throw new UserNotFoundException;
        }

        return $user;
    }
}
