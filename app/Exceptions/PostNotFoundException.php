<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PostNotFoundException extends \Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => 'Post not found.',
        ], Response::HTTP_NOT_FOUND);
    }
}
