<?php

namespace App\Services;

use App\Models\Report;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getReports(array $filters)
    {
        return Report::query()
            ->when($filters['status'] ?? null, fn($query, string $status) => $query->where('status', $status)
            )
            ->when($filters['type'] ?? null, fn($query, string $type) => $query->where('type', $type))
            ->when(
                $filters['withReportCountForReportedUser'] ?? null,
                fn($query) => $query->with([
                    'reportedUser' => fn($query) => $query->withCount(['reports as report_count']),
                ])
            )->paginate(
                perPage: $filters['pageSize'] ?? 25,
                page: $filters['page'] ?? 1
            );
    }

    public function getReportsSummary()
    {
        return DB::select('
        select id, "type" as label, count(id) as count
        from reports
        group by ("type");
    ');
    }
}
