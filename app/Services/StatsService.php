<?php

namespace App\Services;

use App\Enums\ReportStatus;
use App\Enums\UserStatus;
use App\Models\Post;
use App\Models\Report;
use App\Models\User;

class StatsService
{
    public function getTotalUsers()
    {
        return User::query()->count();
    }

    public function getTotalActiveUsers()
    {
        return User::query()
            ->where([
                'status' => UserStatus::ACTIVE,
            ])->count();
    }

    public function postsToday()
    {
        return Post::query()
            ->where([
                'created_at' => today(),
            ])
            ->count();
    }

    public function getActiveReports()
    {
        return Report::query()
            ->where([
                'status' => ReportStatus::PENDING,
            ])
            ->count();
    }

    public function getGeographicDistribution()
    {
        return User::query()
            ->from('users as u')
            ->join('countries as c', 'c.id', '=', 'u.country_id')
            ->select('c.name as country')
            ->selectRaw('COUNT(u.id) as count')
            ->groupBy('c.name')
            ->orderByDesc('count')
            ->get();
    }

    public function getViralPosts(array $paginationOptions)
    {
        return Post::query()
            ->whereBetween('created_at', [$paginationOptions['from'], $paginationOptions['to']])
            ->select('id')
            ->selectRaw('((likes * 2) + (shares * 5) + views) / 10.0 as viralScore')
            ->orderBy('viralScore', 'desc')
            ->paginate(
                perPage: $paginationOptions['pageSize'] ?? 25,
                page: $paginationOptions['page'] ?? 1
            );
    }

    public function getUserGrowth(string $groupBy, string $from, string $to)
    {
        $format = match ($groupBy) {
            'day' => '%Y-%m-%d',
            'week' => '%Y-W%W',
            'month' => '%Y-%m',
            'year' => '%Y',
        };

        return User::query()
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->selectRaw('strftime(?, created_at) as period', [$format])
            ->selectRaw('COUNT(*) as count')
            ->groupByRaw('strftime(?, created_at)', [$format])
            ->orderBy('period')
            ->get();
    }
}
