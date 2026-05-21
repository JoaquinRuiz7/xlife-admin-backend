<?php

namespace App\Services;

use App\Models\BlockedIp;
use App\Models\BlockedPhone;
use App\Models\GlobalSecuritySetting;
use App\Models\SecurityLog;

class SecurityService
{
    public function getSecurityLogs(array $filters)
    {
        return SecurityLog::query()
            ->when(
                $filters['severity'] ?? null,
                fn ($query, string $severity) => $query->where('severity', $severity)
            )
            ->paginate(
                perPage: $filters['pageSize'] ?? 25,
                page: $filters['page'] ?? 1
            );
    }

    public function getBlockedIps(array $filters)
    {
        return BlockedIp::query()
            ->when($filters['ip'] ?? null, fn ($query, string $ip) => $query->where('ip_address', $ip))
            ->paginate(
                perPage: $filters['pageSize'] ?? 25,
                page: $filters['page'] ?? 1
            );
    }

    public function getBlockedPhoneNumbers(array $filters)
    {
        return BlockedPhone::query()
            ->when($filters['phone'] ?? null, fn ($query, string $phone) => $query->where('phone_number', 'like', "%$phone%"))
            ->paginate(
                perPage: $filters['pageSize'] ?? 25,
                page: $filters['page'] ?? 1
            );
    }

    public function getSecuritySettings(array $paginationOptions)
    {
        return GlobalSecuritySetting::query()
            ->paginate(
                perPage: $filters['pageSize'] ?? 25,
                page: $filters['page'] ?? 1
            );
    }
}
