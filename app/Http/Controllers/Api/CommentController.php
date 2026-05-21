<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helper\PaginatedResponse;
use App\Http\Requests\Comments\FetchCommentRequest;
use App\Http\Resources\PostCommentResource;
use App\Services\CommentService;

class CommentController extends Controller
{
    public function __construct(private readonly CommentService $commentService) {}

    public function getComments(FetchCommentRequest $fetchCommentRequest)
    {
        $filters = $fetchCommentRequest->validated();

        return PaginatedResponse::make($this->commentService->fetch($filters), PostCommentResource::class);
    }
}
