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

    public function getViralPosts()
    {
        return Post::query()
            ->select('id')
            ->selectRaw('((likes * 2) + (shares * 5) + views) / 10.0 as viral_score')
            ->orderBy('viral_score', 'desc')
            ->get();

    }
}
