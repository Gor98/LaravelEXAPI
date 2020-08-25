<?php

namespace App\Common\Tools;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class APIResponse
 *
 * @package App\Common\Tools
 */
class APIResponse
{
    /**
     * @param array $responseObject
     * @param int $responseCode
     * @param array $headers
     * @return JsonResponse
     */
    public static function successResponse(
        array $responseObject,
        int $responseCode = Response::HTTP_OK,
        array $headers = []
    ): JsonResponse {
        return response()->json(['data' => $responseObject], $responseCode, $headers);
    }

    /**
     * @param array $responseObject
     * @param int $responseCode
     * @param array $headers
     * @return JsonResponse
     */
    public static function errorResponse(
        array $responseObject,
        int $responseCode = Response::HTTP_BAD_REQUEST,
        array $headers = []
    ): JsonResponse {
        return response()->json(['error' => $responseObject], $responseCode, $headers);
    }

    /**
     * @param int $responseCode
     * @param array $headers
     * @return JsonResponse
     */
    public static function noContentResponse(
        int $responseCode = Response::HTTP_NO_CONTENT,
        $headers = []
    ): JsonResponse {
        return response()->json([], $responseCode, $headers);
    }
}
