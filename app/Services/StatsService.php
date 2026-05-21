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
}
