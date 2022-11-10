<?php

namespace App\Http\Controllers;

use App\Constants\StatusCode;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const DEFAULT_PAGE = 1;
    const DEFAULT_PAGE_SIZE = 10;

    protected $result = [
        'code' => StatusCode::SUCCESS,
        'msg' => 'success',
        'data' => []
    ];

}
