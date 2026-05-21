<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helper\PaginatedResponse;
use App\Http\Requests\GetBlockedIpsRequest;
use App\Http\Requests\GetBlockedPhonesRequest;
use App\Http\Requests\GetSecurityLogsRequest;
use App\Http\Resources\BlockedPhoneResource;
use App\Http\Resources\BlockerIpResource;
use App\Http\Resources\GetSecurityLogsResource;
use App\Services\SecurityService;

class SecurityController extends Controller
{
    public function __construct(private readonly SecurityService $securityService) {}

    public function getSecurityLogs(GetSecurityLogsRequest $getSecurityLogsRequest)
    {
        $filters = $getSecurityLogsRequest->validated();

        return PaginatedResponse::make($this->securityService->getSecurityLogs($filters), GetSecurityLogsResource::class);
    }

    public function getBlockedIps(GetBlockedIpsRequest $getBlockedIpsRequest)
    {
        $filters = $getBlockedIpsRequest->validated();

        return PaginatedResponse::make($this->securityService->getBlockedIps($filters), BlockerIpResource::class);
    }

    public function getBlockedPhones(GetBlockedPhonesRequest $getBlockedPhonesRequest)
    {
        $filters = $getBlockedPhonesRequest->validated();

        return PaginatedResponse::make($this->securityService->getBlockedPhoneNumbers($filters), BlockedPhoneResource::class);

    }
}
