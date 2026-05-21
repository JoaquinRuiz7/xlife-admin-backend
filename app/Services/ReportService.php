<?php

namespace App\Services;

use App\Models\Report;

class ReportService
{
    public function getReports(array $filters)
    {
        return Report::query()
            ->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('status', $status)
            )
            ->when($filters['type'] ?? null, fn ($query, string $type) => $query->where('type', $type))
            ->when(
                $filters['withReportCountForReportedUser'] ?? null,
                fn ($query) => $query->with([
                    'reportedUser' => fn ($query) => $query->withCount(['reports as report_count']),
                ])
            )->paginate(
                perPage: $filters['pageSize'] ?? 25,
                page: $filters['page'] ?? 1
            );
    }

    public function getReportsSummary()
    {
        return Report::query()
            ->select(['id', 'type as label'])
            ->selectRaw('COUNT(id) as count')
            ->groupBy('type')
            ->get();
    }
}
