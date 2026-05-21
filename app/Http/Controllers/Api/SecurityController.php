<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helper\PaginatedResponse;
use App\Http\Requests\GetBlockedIpsRequest;
use App\Http\Requests\GetBlockedPhonesRequest;
use App\Http\Requests\GetGlobalSecuritySettingsRequest;
use App\Http\Requests\GetSecurityLogsRequest;
use App\Http\Resources\BlockedPhoneResource;
use App\Http\Resources\BlockerIpResource;
use App\Http\Resources\GetSecurityLogsResource;
use App\Http\Resources\GlobalSecuritySettingResource;
use App\Services\SecurityService;

class SecurityController extends Controller
{
    public function __construct(private readonly SecurityService $securityService) {}

    /**
     * @OA\Get(
     *     path="/api/security/logs",
     *     summary="Get security logs",
     *     description="Returns a paginated list of security logs. Results can be filtered by severity.",
     *     tags={"Security"},
     *
     *     @OA\Parameter(
     *         name="severity",
     *         in="query",
     *         required=false,
     *         description="Filter security logs by severity",
     *
     *         @OA\Schema(
     *             type="string",
     *             enum={"info", "warning", "high"},
     *             example="warning"
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
     *         description="Number of security logs per page. Maximum value is 100.",
     *
     *         @OA\Schema(type="integer", minimum=1, maximum=100, example=25)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Paginated security logs",
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
     *                     @OA\Property(property="event", type="string", example="Failed login attempt"),
     *                     @OA\Property(property="severity", type="string", example="warning"),
     *                     @OA\Property(property="ipAddress", type="string", example="192.168.1.10"),
     *                     @OA\Property(property="user", type="string", nullable=true, example="John Doe"),
     *                     @OA\Property(property="createdAt", type="string", format="date-time", example="2026-05-21 14:30:00")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="total", type="integer", example=300),
     *                 @OA\Property(property="page", type="integer", example=1),
     *                 @OA\Property(property="pageSize", type="integer", example=25),
     *                 @OA\Property(property="lastPage", type="integer", example=12)
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
    public function getSecurityLogs(GetSecurityLogsRequest $getSecurityLogsRequest)
    {
        $filters = $getSecurityLogsRequest->validated();

        return PaginatedResponse::make(
            $this->securityService->getSecurityLogs($filters),
            GetSecurityLogsResource::class
        );
    }

    /**
     * @OA\Get(
     *     path="/api/security/blocked-ips",
     *     summary="Get blocked IPs",
     *     description="Returns a paginated list of blocked IP addresses. Results can be filtered by IP address.",
     *     tags={"Security"},
     *
     *     @OA\Parameter(
     *         name="ip",
     *         in="query",
     *         required=false,
     *         description="Filter blocked IPs by IPv4 address",
     *
     *         @OA\Schema(
     *             type="string",
     *             format="ipv4",
     *             example="192.168.1.10"
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
     *         description="Number of blocked IPs per page. Maximum value is 100.",
     *
     *         @OA\Schema(type="integer", minimum=1, maximum=100, example=25)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Paginated blocked IPs",
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
     *                     @OA\Property(property="ip", type="string", example="192.168.1.10"),
     *                     @OA\Property(property="reason", type="string", example="Too many failed login attempts"),
     *                     @OA\Property(property="createdAt", type="string", format="date-time", example="2026-05-21 14:30:00")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="total", type="integer", example=50),
     *                 @OA\Property(property="page", type="integer", example=1),
     *                 @OA\Property(property="pageSize", type="integer", example=25),
     *                 @OA\Property(property="lastPage", type="integer", example=2)
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
    public function getBlockedIps(GetBlockedIpsRequest $getBlockedIpsRequest)
    {
        $filters = $getBlockedIpsRequest->validated();

        return PaginatedResponse::make(
            $this->securityService->getBlockedIps($filters),
            BlockerIpResource::class
        );
    }

    /**
     * @OA\Get(
     *     path="/api/security/blocked-phones",
     *     summary="Get blocked phone numbers",
     *     description="Returns a paginated list of blocked phone numbers. Results can be filtered by phone number.",
     *     tags={"Security"},
     *
     *     @OA\Parameter(
     *         name="phone",
     *         in="query",
     *         required=false,
     *         description="Filter blocked phone numbers by phone",
     *
     *         @OA\Schema(
     *             type="string",
     *             example="+59899123456"
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
     *         description="Number of blocked phone numbers per page. Maximum value is 100.",
     *
     *         @OA\Schema(type="integer", minimum=1, maximum=100, example=25)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Paginated blocked phone numbers",
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
     *                     @OA\Property(property="phone", type="string", example="+59899123456"),
     *                     @OA\Property(property="reason", type="string", example="Suspicious activity"),
     *                     @OA\Property(property="createdAt", type="string", format="date-time", example="2026-05-21 14:30:00")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="total", type="integer", example=80),
     *                 @OA\Property(property="page", type="integer", example=1),
     *                 @OA\Property(property="pageSize", type="integer", example=25),
     *                 @OA\Property(property="lastPage", type="integer", example=4)
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
    public function getBlockedPhones(GetBlockedPhonesRequest $getBlockedPhonesRequest)
    {
        $filters = $getBlockedPhonesRequest->validated();

        return PaginatedResponse::make(
            $this->securityService->getBlockedPhoneNumbers($filters),
            BlockedPhoneResource::class
        );
    }

    /**
     * @OA\Get(
     *     path="/api/security/settings",
     *     summary="Get global security settings",
     *     description="Returns a paginated list of global security settings.",
     *     tags={"Security"},
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
     *         description="Number of security settings per page. Maximum value is 100.",
     *
     *         @OA\Schema(type="integer", minimum=1, maximum=100, example=25)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Paginated global security settings",
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
     *                     @OA\Property(property="key", type="string", example="max_login_attempts"),
     *                     @OA\Property(property="value", type="string", example="5"),
     *                     @OA\Property(property="description", type="string", example="Maximum allowed failed login attempts before blocking access"),
     *                     @OA\Property(property="createdAt", type="string", format="date-time", example="2026-05-21 14:30:00"),
     *                     @OA\Property(property="updatedAt", type="string", format="date-time", example="2026-05-21 15:00:00")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="total", type="integer", example=20),
     *                 @OA\Property(property="page", type="integer", example=1),
     *                 @OA\Property(property="pageSize", type="integer", example=25),
     *                 @OA\Property(property="lastPage", type="integer", example=1)
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
    public function getSecuritySettings(GetGlobalSecuritySettingsRequest $getGlobalSecuritySettingsRequest)
    {
        $paginationOptions = $getGlobalSecuritySettingsRequest->validated();

        return PaginatedResponse::make(
            $this->securityService->getSecuritySettings($paginationOptions),
            GlobalSecuritySettingResource::class
        );
    }
}
