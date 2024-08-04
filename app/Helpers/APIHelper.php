<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class APIHelper
{
  public static function success($message, $data = null, $code = 200): JsonResponse
  {
    return response()->json([
      'success' => true,
      'message' => $message,
      'data' => $data,
    ], $code);
  }

  public static function error($message, $data = null, $code = 500): JsonResponse
  {
    return response()->json([
      'success' => false,
      'message' => $message,
      'data' => $data,
    ], $code);
  }
}
