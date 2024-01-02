<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function sendSuccessResponse($result, $message)
    {
        $response = [
            'success' => true,
            'status' => 1,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    protected function sendValidationErrorResposnse($errors)
    {
        return response()->json([
            "message" => "Invalid user request",
            "errors" => $errors,
            "status" => 0,
            "status_code" => 400,
        ], 400);
    }

    protected function sendBadRequestResponse($message)
    {
        return response()->json([
            "message" => $message,
            "status" => 0,
            "status_code" => 400,
        ], 400);
    }
}
