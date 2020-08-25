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
     * @param object $responseObject
     * @param int $responseCode
     * @param array $headers
     * @return JsonResponse
     */
    public static function successResponse(
        object $responseObject,
        int $responseCode = Response::HTTP_OK,
        array $headers = []
    ): JsonResponse {
        return response()->json(['data' => $responseObject], $responseCode, $headers);
    }

    /**
     * @param $responseObject
     * @param int $responseCode
     * @param array $headers
     * @return JsonResponse
     */
    public static function collectionResponse(
        object $responseObject,
        int $responseCode = Response::HTTP_OK,
        array $headers = []
    ): JsonResponse {
        return response()->json($responseObject, $responseCode, $headers);
    }

    /**
     * @param object $responseObject
     * @param int $responseCode
     * @param array $headers
     * @return JsonResponse
     */
    public static function errorResponse(
        object $responseObject,
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
