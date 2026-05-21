<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helper\PaginatedResponse;
use App\Http\Requests\Posts\FetchPostsRequest;
use App\Http\Resources\PostResource;
use App\Services\PostService;

class PostController extends Controller
{
    public function __construct(private readonly PostService $postService) {}

    public function getPosts(FetchPostsRequest $fetchPostsRequest)
    {
        $filters = $fetchPostsRequest->validated();

        return PaginatedResponse::make($this->postService->getPosts($filters), PostResource::class);
    }
}
