<?php

namespace App\Services\User;

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
            ->paginate(perPage: $filters['pageSize'] ?? 25,
                page: $filters['page'] ?? 1);
    }
}
