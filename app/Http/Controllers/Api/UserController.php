<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helper\PaginatedResponse;
use App\Http\Requests\Users\IndexUsersRequest;
use App\Http\Resources\UserResource;
use App\Services\User\UserService;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function index(IndexUsersRequest $fetchUsersRequest)
    {
        $users = $this->userService->index($fetchUsersRequest->validated());
        return PaginatedResponse::make($users, UserResource::class);
    }
}
