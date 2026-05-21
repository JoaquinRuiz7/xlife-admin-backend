<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helper\PaginatedResponse;
use App\Http\Requests\GetReportsRequest;
use App\Http\Resources\ReportResource;
use App\Services\ReportService;

class ReportController extends Controller
{
    public function __construct(private readonly ReportService $reportService) {}

    public function getReports(GetReportsRequest $fetchReportsRequest)
    {
        $filters = $fetchReportsRequest->validated();

        return PaginatedResponse::make($this->reportService->getReports($filters), ReportResource::class);
    }

    public function getReportsSummary()
    {
        return $this->reportService->getReportsSummary();
    }
}
