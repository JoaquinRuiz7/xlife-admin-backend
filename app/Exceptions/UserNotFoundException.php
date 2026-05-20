<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserNotFoundException extends \Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => 'User not found.',
        ], Response::HTTP_NOT_FOUND);
    }
}
