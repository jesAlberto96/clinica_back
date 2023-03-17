<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ResponseController extends Controller
{

    public function sendResponse($success = true, $data = [], $errors = [], $code = 200)
    {
    	$response = [
            'meta' => array(
                "success" => $success,
                "errors" => $errors,
            ),
            'data' => $data,
        ];

        return response()->json($response, $code);
    }

    public function sendError($errors = [], $code = 200)
    {
    	$response = [
            'meta' => array(
                "success" => false,
                "errors" => $errors,
            )
        ];

        return response()->json($response, $code);
    }
}