<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
}
