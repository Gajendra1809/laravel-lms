<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait JsonResponseTrait
{

    /**
     * Generate a JSON response for successful operations.
     *
     * @param  mixed  $data
     * @param  string  $messageKey
     * @param  int  $statusCode
     * @return JsonResponse
     */
    public function successResponse($data, $message = 'success', $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Generate a JSON response for errors.
     *
     * @param  string  $message
     * @param  string  $messageKey
     * @param  int  $statusCode
     * @return JsonResponse
     */
    public function errorResponse($message = 'error', $error=null, $statusCode = 500): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => $error
        ], $statusCode);
    }
    
}
