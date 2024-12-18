<?php

namespace App\Classes;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiResponseClass
{
    public static function rollback($e, $message = "Something went wrong...")
    {
        DB::self::rollback();
        self::throw($e, $message);
    }

    public static function throw($e, $message = "Something went wrong...")
    {
        Log::error($e);
        return response()->json([
                'message' => $message,
            ], 500);
    }

    public static function sendResponse($result, string $message, int $code = 200): JsonResponse
    {
        $response = [
            'success' => (bool)array_search($code, [0,200, 201]),
            'message' => $message,
            'data' => $result,
        ];

        return response()->json($response, $code);
    }
}
