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
            ->paginate(
                perPage: $filters['pageSize'] ?? 25,
                page: $filters['page'] ?? 1
            );
    }
}
