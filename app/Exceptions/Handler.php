<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        $response = parent::render($request, $exception);

        if ($request->is('api/*') && $response->getStatusCode() >= 500) {
            report($exception);

            return response()->json([
                'message' => 'Internal server error.',
            ], 500);
        }

        return $response;
    }
}
