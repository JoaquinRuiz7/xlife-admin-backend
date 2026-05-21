<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helper\PaginatedResponse;
use App\Http\Requests\GetReportsRequest;
use App\Http\Resources\ReportResource;
use App\Services\ReportService;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    public function __construct(private readonly ReportService $reportService) {}

    /**
     * @OA\Get(
     *     path="/api/reports",
     *     summary="Get reports",
     *     description="Returns a paginated list of reports. Results can be filtered by status, type, and optionally include the report count for the reported user.",
     *     tags={"Reports"},
     *
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         description="Filter reports by status",
     *
     *         @OA\Schema(
     *             type="string",
     *             enum={"pending", "in_review", "resolved", "dismissed"},
     *             example="pending"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         required=false,
     *         description="Filter reports by type",
     *
     *         @OA\Schema(
     *             type="string",
     *             enum={"spam", "harassment", "inappropriate", "copyright", "misinformation"},
     *             example="spam"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="withReportCountForReportedUser",
     *         in="query",
     *         required=false,
     *         description="Whether to include the total number of reports received by the reported user. Use 1 for true, 0 for false.",
     *
     *         @OA\Schema(
     *             type="integer",
     *             enum={0, 1},
     *             example=1
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Pagination page number",
     *
     *         @OA\Schema(type="integer", minimum=1, example=1)
     *     ),
     *
     *     @OA\Parameter(
     *         name="pageSize",
     *         in="query",
     *         required=false,
     *         description="Number of reports per page. Maximum value is 100.",
     *
     *         @OA\Schema(type="integer", minimum=1, maximum=100, example=25)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Paginated reports",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *
     *                 @OA\Items(
     *                     type="object",
     *
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="reportedBy", type="string", example="John Doe"),
     *                     @OA\Property(property="reportedUser", type="string", example="Jane Smith"),
     *                     @OA\Property(property="type", type="string", example="spam"),
     *                     @OA\Property(property="status", type="string", example="pending"),
     *                     @OA\Property(property="reason", type="string", example="Repeated spam content"),
     *                     @OA\Property(
     *                         property="reportedUserReportsCount",
     *                         type="integer",
     *                         nullable=true,
     *                         example=5
     *                     )
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="total", type="integer", example=250),
     *                 @OA\Property(property="page", type="integer", example=1),
     *                 @OA\Property(property="pageSize", type="integer", example=25),
     *                 @OA\Property(property="lastPage", type="integer", example=10)
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
    public function getReports(GetReportsRequest $fetchReportsRequest)
    {
        $filters = $fetchReportsRequest->validated();

        return PaginatedResponse::make(
            $this->reportService->getReports($filters),
            ReportResource::class
        );
    }

    /**
     * @OA\Get(
     *     path="/api/reports/summary",
     *     summary="Get reports summary",
     *     description="Returns the total number of reports grouped by report type.",
     *     tags={"Reports"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Reports summary grouped by type",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(
     *                 type="object",
     *
     *                 @OA\Property(property="id", type="integer", example=12),
     *                 @OA\Property(property="label", type="string", example="copyright"),
     *                 @OA\Property(property="count", type="integer", example=107)
     *             )
     *         )
     *     )
     * )
     */
    public function getReportsSummary()
    {
        return response($this->reportService->getReportsSummary(), Response::HTTP_OK);
    }
}
