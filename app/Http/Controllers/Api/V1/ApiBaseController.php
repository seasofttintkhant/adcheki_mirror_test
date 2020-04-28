<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class ApiBaseController extends Controller
{
    /**
     * @param int $http_status
     * @param int $status
     * @param array $headers
     * @param array $message
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResponse($http_status = 200, $status = 1, $headers = [], $message = [], $data = [])
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'headers' => $headers,
            'data' => $data
        ], $http_status);
    }
}
