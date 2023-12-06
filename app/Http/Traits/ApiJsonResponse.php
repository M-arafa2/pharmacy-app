<?php

namespace App\Http\Traits;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

trait ApiJsonResponse
{
    protected function success($message, $data = [], $status = 200)
    {
        return response([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }
    protected function failure($message, $status = 422)
    {
        return response([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}
