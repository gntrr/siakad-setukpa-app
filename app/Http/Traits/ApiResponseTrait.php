<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    /**
     * Check if request is an API request
     */
    protected function isApiRequest(Request $request = null): bool
    {
        $request = $request ?: request();
        
        return $request->expectsJson() || 
               $request->is('api/*') || 
               $request->wantsJson() ||
               $request->header('Accept') === 'application/json' ||
               $request->header('Content-Type') === 'application/json';
    }

    /**
     * Return API success response
     */
    protected function apiSuccess($data = null, string $message = 'Success', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message
        ], $status);
    }

    /**
     * Return API error response
     */
    protected function apiError(string $message = 'Error', $data = null, int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    /**
     * Return validation error response
     */
    protected function apiValidationError($errors, string $message = 'Validation failed', int $status = 422): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $status);
    }
}
