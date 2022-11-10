<?php

use App\Constants\StatusCode;

if (!function_exists('doJsonResponse')) {
    function doJsonResponse(array $result = [])
    {
        $code = $result['code'] ?? StatusCode::SUCCESS;
        $msg = $result['msg'] ?? 'success';
        $data = $result['data'] ?? [];
        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
    }
}


