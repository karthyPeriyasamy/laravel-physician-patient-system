<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    /**
     * Success response
     *
     * @param  mixed $result
     * @param  mixed $message
     * @return Response
     */
    public function successResponse($result, $message)
    {
        $response = ['success' => true, 'data' => $result, 'message' => $message];
        return response()->json($response);
    }

    /**
     * Error Response
     *
     * @param  mixed $error
     * @param  mixed $errorMessages
     * @param  number $code
     * @return Response
     */
    public function errorResponse($error, $errorMessages = [], $code = 404)
    {
        $response = [ 'success' => false, 'message' => $error];
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}
