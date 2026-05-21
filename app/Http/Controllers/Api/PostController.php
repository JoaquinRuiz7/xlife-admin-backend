<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helper\PaginatedResponse;
use App\Http\Requests\GetPostsRequest;
use App\Http\Resources\PostCommentResource;
use App\Http\Resources\PostResource;
use App\Services\PostService;

class PostController extends Controller
{
    public function __construct(private readonly PostService $postService) {}

    /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Get posts",
     *     description="Returns a paginated list of posts. Results can be filtered by status and searched by text.",
     *     tags={"Posts"},
     *
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         description="Filter posts by status",
     *
     *         @OA\Schema(
     *             type="string",
     *             enum={"draft", "published", "taken_down"},
     *             example="published"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Search posts by text",
     *
     *         @OA\Schema(type="string", maxLength=255, example="test comment")
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
     *         description="Number of posts per page. Maximum value is 100.",
     *
     *         @OA\Schema(type="integer", minimum=1, maximum=100, example=25)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Paginated posts",
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
     *                     @OA\Property(property="user", type="string", example="John Doe"),
     *                     @OA\Property(property="content", type="string", example="This is a post content."),
     *                     @OA\Property(property="status", type="string", example="published"),
     *                     @OA\Property(property="views", type="integer", example=1200),
     *                     @OA\Property(property="likes", type="integer", example=300),
     *                     @OA\Property(property="shares", type="integer", example=45)
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="total", type="integer", example=500),
     *                 @OA\Property(property="page", type="integer", example=1),
     *                 @OA\Property(property="pageSize", type="integer", example=25),
     *                 @OA\Property(property="lastPage", type="integer", example=20)
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
    public function getPosts(GetPostsRequest $fetchPostsRequest)
    {
        $filters = $fetchPostsRequest->validated();

        return PaginatedResponse::make(
            $this->postService->getPosts($filters),
            PostResource::class
        );
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{postId}/comments",
     *     summary="Get post comments",
     *     description="Returns a paginated list of comments for a specific post.",
     *     tags={"Posts"},
     *
     *     @OA\Parameter(
     *         name="postId",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
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
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Search comments by text",
     *
     *         @OA\Schema(type="string", maxLength=255, example="important comment")
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
     *         description="Paginated post comments",
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
     *                     @OA\Property(property="commentedBy", type="string", example="John Doe"),
     *                     @OA\Property(property="comment", type="string", example="This is a post comment."),
     *                     @OA\Property(property="post", type="integer", example=27),
     *                     @OA\Property(property="status", type="string", example="published")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="total", type="integer", example=100),
     *                 @OA\Property(property="page", type="integer", example=1),
     *                 @OA\Property(property="pageSize", type="integer", example=25),
     *                 @OA\Property(property="lastPage", type="integer", example=4)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function getPostComments(int $postId, GetPostsRequest $fetchPostsRequest)
    {
        $pageInfo = $fetchPostsRequest->validated();

        return PaginatedResponse::make(
            $this->postService->getPostComments($postId, $pageInfo),
            PostCommentResource::class
        );
    }
}
