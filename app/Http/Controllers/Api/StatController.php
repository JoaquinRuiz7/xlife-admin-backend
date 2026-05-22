<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helper\PaginatedResponse;
use App\Http\Requests\GetUserGrowthRequest;
use App\Http\Requests\ViralPostsRequest;
use App\Http\Resources\ViralPostResource;
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
     *
     *     @OA\Response(
     *         response=200,
     *         description="Overview statistics",
     *
     *         @OA\JsonContent(
     *
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

    /**
     * @OA\Get(
     *     path="/api/stats/geographic",
     *     summary="Get user geographical distribution grouped by country",
     *     tags={"Stats"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Geographical distribution grouped by country.",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(
     *
     *                 @OA\Property(property="country", type="string", example="Italy"),
     *                 @OA\Property(property="count", type="integer", example=223)
     *             )
     *         )
     *     ),
     * )
     */
    public function getGeographicDistribution()
    {
        return response($this->statsService->getGeographicDistribution(), Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/stats/viral-posts",
     *     summary="Get viral posts",
     *     description="Returns a paginated list of posts ordered by viral score for a given date range.",
     *     tags={"Stats"},
     *
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         required=true,
     *         description="Start date",
     *         @OA\Schema(type="string", format="date", example="2026-05-01")
     *     ),
     *
     *     @OA\Parameter(
     *         name="to",
     *         in="query",
     *         required=true,
     *         description="End date",
     *         @OA\Schema(type="string", format="date", example="2026-05-31")
     *     ),
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=true,
     *         description="Pagination page number",
     *         @OA\Schema(type="integer", minimum=1, example=1)
     *     ),
     *
     *     @OA\Parameter(
     *         name="pageSize",
     *         in="query",
     *         required=true,
     *         description="Number of viral posts per page. Maximum value is 100.",
     *         @OA\Schema(type="integer", minimum=1, maximum=100, example=25)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Paginated viral posts for a given date range.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=75),
     *                     @OA\Property(property="viralScore", type="number", format="float", example=1319.0)
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="total", type="integer", example=198),
     *                 @OA\Property(property="page", type="integer", example=1),
     *                 @OA\Property(property="pageSize", type="integer", example=25),
     *                 @OA\Property(property="lastPage", type="integer", example=8)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function getViralScoresForPosts(ViralPostsRequest $paginationParams)
    {
        $paginationOptions = $paginationParams->validated();

        return PaginatedResponse::make(
            $this->statsService->getViralPosts($paginationOptions),
            ViralPostResource::class
        );
    }

    /**
     * @OA\Get(
     *     path="/api/stats/user-growth",
     *     summary="Get user growth grouped by period",
     *     tags={"Stats"},
     *
     *     @OA\Parameter(
     *         name="groupBy",
     *         in="query",
     *         required=true,
     *         description="Grouping period",
     *
     *         @OA\Schema(type="string", enum={"day", "week", "month", "year"}, example="month")
     *     ),
     *
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         required=true,
     *         description="Start date",
     *
     *         @OA\Schema(type="string", format="date", example="2026-05-01")
     *     ),
     *
     *     @OA\Parameter(
     *         name="to",
     *         in="query",
     *         required=true,
     *         description="End date",
     *
     *         @OA\Schema(type="string", format="date", example="2026-05-31")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User growth data",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(
     *
     *                 @OA\Property(property="period", type="string", example="2026-05"),
     *                 @OA\Property(property="count", type="integer", example=1200)
     *             )
     *         )
     *     ),
     *
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
