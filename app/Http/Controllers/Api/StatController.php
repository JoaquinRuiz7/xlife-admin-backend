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

    /**
     * @OA\Get(
     *     path="/api/stats/overview",
     *     summary="Get overview statistics",
     *     tags={"Stats"},
     *     @OA\Response(
     *         response=200,
     *         description="Overview statistics",
     *         @OA\JsonContent(
     *             @OA\Property(property="totalUsers", type="integer", example=1200),
     *             @OA\Property(property="activeUsers", type="integer", example=950),
     *             @OA\Property(property="pendingReports", type="integer", example=35)
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/stats/user-growth",
     *     summary="Get user growth grouped by period",
     *     tags={"Stats"},
     *     @OA\Parameter(
     *         name="groupBy",
     *         in="query",
     *         required=true,
     *         description="Grouping period",
     *         @OA\Schema(type="string", enum={"day", "week", "month", "year"}, example="month")
     *     ),
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         required=true,
     *         description="Start date",
     *         @OA\Schema(type="string", format="date", example="2026-05-01")
     *     ),
     *     @OA\Parameter(
     *         name="to",
     *         in="query",
     *         required=true,
     *         description="End date",
     *         @OA\Schema(type="string", format="date", example="2026-05-31")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User growth data",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="period", type="string", example="2026-05"),
     *                 @OA\Property(property="count", type="integer", example=1200)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function getUserGrowth(GetUserGrowthRequest $rangedRequest)
    {
        $rangeDates = $rangedRequest->validated();

        return $this->statsService->getUserGrowth($rangedRequest['groupBy'], $rangeDates['from'], $rangeDates['to']);
    }
}
