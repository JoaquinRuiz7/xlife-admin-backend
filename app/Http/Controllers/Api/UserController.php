<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helper\PaginatedResponse;
use App\Http\Requests\Users\GetUserPostsRequest;
use App\Http\Requests\Users\GetUsersRequest;
use App\Http\Resources\UserPostResource;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService) {}

    public function getUsers(GetUsersRequest $indexUsersRequest)
    {
        $users = $this->userService->getUsers($indexUsersRequest->validated());

        return PaginatedResponse::make($users, UserResource::class);
    }

    public function getById(int $userId)
    {
        return response(UserResource::make($this->userService->getById($userId)), ResponseAlias::HTTP_OK);
    }

    public function getUserPosts(int $userId, GetUserPostsRequest $getUserPostsRequest)
    {
        return PaginatedResponse::make($this->userService->getUserPosts($userId, $getUserPostsRequest->validated()), UserPostResource::class);
    }

    public function getUserActivity(int $userId)
    {
        return response($this->userService->getUserActivity($userId), ResponseAlias::HTTP_OK);
    }
}
