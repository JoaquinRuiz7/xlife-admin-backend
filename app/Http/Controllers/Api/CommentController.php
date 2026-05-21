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

    /**
     * @OA\Get(
     *     path="/api/comments",
     *     summary="Get post comments",
     *     description="Returns a paginated list of post comments. Results can be filtered by comment status.",
     *     tags={"Comments"},
     *
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         description="Filter comments by status",
     *
     *         @OA\Schema(
     *             type="string",
     *             enum={"draft", "published", "taken_down"},
     *             example="published"
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
     *         description="Number of comments per page. Maximum value is 100.",
     *
     *         @OA\Schema(type="integer", minimum=1, maximum=100, example=25)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Paginated comments",
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
     *                     @OA\Property(property="commentedBy", type="string", example="Dr. Magdalen Walsh"),
     *                     @OA\Property(property="comment", type="string", example="This is a post comment."),
     *                     @OA\Property(property="post", type="integer", example=27),
     *                     @OA\Property(property="status", type="string", example="published")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="total", type="integer", example=1000),
     *                 @OA\Property(property="page", type="integer", example=1),
     *                 @OA\Property(property="pageSize", type="integer", example=25),
     *                 @OA\Property(property="lastPage", type="integer", example=40)
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
    public function getComments(GetCommentRequest $fetchCommentRequest)
    {
        $filters = $fetchCommentRequest->validated();

        return PaginatedResponse::make(
            $this->commentService->getComments($filters),
            PostCommentResource::class
        );
    }
}
