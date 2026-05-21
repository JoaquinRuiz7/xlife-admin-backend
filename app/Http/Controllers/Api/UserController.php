<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helper\PaginatedResponse;
use App\Http\Requests\Users\GetUserPostsRequest;
use App\Http\Requests\Users\GetUsersRequest;
use App\Http\Resources\UserPostResource;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService) {}

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get users",
     *     description="Returns a paginated list of users. Results can be filtered by status and searched by text.",
     *     tags={"Users"},
     *
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         description="Filter users by status",
     *
     *         @OA\Schema(
     *             type="string",
     *             enum={"pending", "active", "suspended", "disabled"},
     *             example="active"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Search users by name, email, or related searchable fields",
     *
     *         @OA\Schema(type="string", maxLength=255, example="john")
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
     *         description="Number of users per page. Maximum value is 100.",
     *
     *         @OA\Schema(type="integer", minimum=1, maximum=100, example=25)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Paginated users",
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
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *                     @OA\Property(property="cellphone", type="string", nullable=true, example="+59899123456"),
     *                     @OA\Property(property="country", type="string", nullable=true, example="Uruguay"),
     *                     @OA\Property(property="status", type="string", example="active"),
     *                     @OA\Property(property="lastLoginAt", type="string", format="date-time", nullable=true, example="2026-05-21 14:30:00")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="total", type="integer", example=1200),
     *                 @OA\Property(property="page", type="integer", example=1),
     *                 @OA\Property(property="pageSize", type="integer", example=25),
     *                 @OA\Property(property="lastPage", type="integer", example=48)
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
    public function getUsers(GetUsersRequest $indexUsersRequest)
    {
        $users = $this->userService->getUsers($indexUsersRequest->validated());

        return PaginatedResponse::make($users, UserResource::class);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{userId}",
     *     summary="Get user by ID",
     *     description="Returns the details of a specific user.",
     *     tags={"Users"},
     *
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User details",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="cellphone", type="string", nullable=true, example="+59899123456"),
     *             @OA\Property(property="country", type="string", nullable=true, example="Uruguay"),
     *             @OA\Property(property="status", type="string", example="active"),
     *             @OA\Property(property="lastLoginAt", type="string", format="date-time", nullable=true, example="2026-05-21 14:30:00")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function getById(int $userId)
    {
        return response(
            UserResource::make($this->userService->getById($userId)),
            Response::HTTP_OK
        );
    }

    public function getUserPosts(int $userId, GetUserPostsRequest $getUserPostsRequest)
    {
        return PaginatedResponse::make($this->userService->getUserPosts($userId, $getUserPostsRequest->validated()), UserPostResource::class);
    }

    public function getUserActivity(int $userId)
    {
        return response($this->userService->getUserActivity($userId), Response::HTTP_OK);
    }
}
