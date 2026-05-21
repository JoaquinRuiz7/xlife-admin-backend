<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetUserGrowthRequest;
use App\Http\Requests\RangedRequest;
use App\Services\StatsService;
use Illuminate\Http\Response;

class StatController extends Controller
{
    //
    public function __construct(private readonly StatsService $statsService)
    {
    }

    public function getOverView()
    {
        return response([
            'totalUsers' => $this->statsService->getTotalUsers(),
            'activeUsers' => $this->statsService->getTotalActiveUsers(),
            'pendingReports' => $this->statsService->getActiveReports(),
        ], Response::HTTP_OK);
    }

    public function getGeographicDistribution()
    {
        return $this->statsService->getGeographicDistribution();
    }

    public function getViralScoresForPosts(RangedRequest $rangedRequest)
    {
        $rangeDates = $rangedRequest->validated();

        return $this->statsService->getViralPosts($rangeDates['from'], $rangeDates['to']);
    }

    public function getUserGrowth(GetUserGrowthRequest $rangedRequest)
    {
        $rangeDates = $rangedRequest->validated();

        return $this->statsService->getUserGrowth($rangedRequest['groupBy'], $rangeDates['from'], $rangeDates['to']);
    }
}
