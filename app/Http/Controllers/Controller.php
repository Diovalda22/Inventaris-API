<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function success($data, $code = 200)
    {
        return response()->json(['data' => $data], $code);
    }

    public function fail($message = 'response failed', $code = 400)
    {
        return response()->json(['message' => $message], $code);
    }

    public function message($message, $code = 200)
    {
        return response()->json(['message' => $message], $code);
    }
}
