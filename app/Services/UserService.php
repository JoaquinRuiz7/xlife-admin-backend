<?php

namespace App\Services;

use App\Exceptions\UserNotFoundException;
use App\Models\Post;
use App\Models\User;
use App\Models\UserActivity;

class UserService
{
    public function getUsers(array $filters)
    {
        return User::query()
            ->when(
                $filters['status'] ?? null,
                fn ($query, string $status) => $query->where('status', $status)
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

    public function getById(int $userId)
    {
        $user = User::whereId($userId)->first();

        if (! $user) {
            throw new UserNotFoundException;
        }

        return $user;
    }

    public function getUserPosts(int $userId, array $paginationOptions)
    {
        $userExists = User::whereId($userId)->exists();
        if (! $userExists) {
            throw new UserNotFoundException;
        }

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
        $user = User::whereId($userId)->exists();
        if (! $user) {
            throw new UserNotFoundException;
        }

        $logs = UserActivity::query()
            ->where('user_id', $userId)
            ->whereDate('started_at', today())
            ->get();

        $activity = collect(range(0, 23))
            ->mapWithKeys(fn (int $hour) => [$hour => 0])
            ->toArray();

        foreach ($logs as $log) {
            $start = $log->started_at->copy();
            $end = $log->ended_at->copy();

            while ($start->lt($end)) {
                $hour = (int) $start->format('G');

                $endOfHour = $start->copy()
                    ->startOfHour()
                    ->addHour();

                $segmentEnd = $end->lt($endOfHour)
                    ? $end
                    : $endOfHour;

                $activity[$hour] += $start->diffInMinutes($segmentEnd);

                $start = $segmentEnd;
            }
        }

        return collect($activity)
            ->map(fn (int $minutes, int $hour) => [
                'hour' => $hour,
                'minutes' => min($minutes, 60),
            ])
            ->values()
            ->toArray();
    }
}
