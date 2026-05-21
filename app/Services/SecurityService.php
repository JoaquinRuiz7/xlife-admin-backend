<?php

namespace App\Services;

use App\Models\SecurityLog;

class SecurityService
{
    public function getSecurityLogs(array $filters)
    {
        return SecurityLog::query()
            ->when($filters['severity'] ?? null, fn ($query, string $severity) => $query->where('severity', $severity)
            )
            ->paginate(
                perPage: $filters['pageSize'] ?? 25,
                page: $filters['page'] ?? 1
            );
    }
}
