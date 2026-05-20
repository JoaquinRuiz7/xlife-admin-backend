<?php

namespace App\Http\Helper;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginatedResponse
{
    public static function make(
        LengthAwarePaginator $paginator,
        string $resourceClass
    ): JsonResponse {
        return response()->json([
            'data' => $resourceClass::collection($paginator->items()),
            'meta' => [
                'total' => $paginator->total(),
                'page' => $paginator->currentPage(),
                'pageSize' => $paginator->perPage(),
                'lastPage' => $paginator->lastPage(),
            ],
        ]);
    }
}
