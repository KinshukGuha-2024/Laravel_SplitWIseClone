<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function responseSuccess($message = "Success", $data = null, $status = 200) {
        return response([
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public function responseError($message = "Something Went Wrong", $data = null, $status = 500) {
        return response([
            'message' => $message,
            'data' => $data
        ], $status);
    }
}
