<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helper\PaginatedResponse;
use App\Http\Requests\Users\IndexUsersRequest;
use App\Http\Resources\UserResource;
use App\Services\User\UserService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function index(IndexUsersRequest $indexUsersRequest)
    {
        $users = $this->userService->index($indexUsersRequest->validated());
        return PaginatedResponse::make($users, UserResource::class);
    }

    public function show(int $userId)
    {
        return response(UserResource::make($this->userService->show($userId)), ResponseAlias::HTTP_OK);
    }
}
