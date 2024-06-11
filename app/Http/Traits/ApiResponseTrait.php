<?php

namespace App\Http\Traits;

trait ApiResponseTrait
{
    public function apiSuccess(array $data = [], string $message = null, $code = 200)
    {
        $message = $message ?? "Request has been sent successfully";
        if (!empty($data)) {
            return response()->json(compact("message", "data"), $code);
        }
        return response()->json(compact("message"), $code);
    }


    public function apiError(string $message = null, int $code = 500)
    {
        $message = $message ?? "Some thing error!";
        return response()->json(compact("message"), $code);
    }
}