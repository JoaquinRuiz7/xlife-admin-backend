<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helper\PaginatedResponse;
use App\Http\Requests\GetCommentRequest;
use App\Http\Resources\PostCommentResource;
use App\Services\CommentService;

class CommentController extends Controller
{
    public function __construct(private readonly CommentService $commentService) {}

    public function getComments(GetCommentRequest $fetchCommentRequest)
    {
        $filters = $fetchCommentRequest->validated();

        return PaginatedResponse::make($this->commentService->getComments($filters), PostCommentResource::class);
    }
}
