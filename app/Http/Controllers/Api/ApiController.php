<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller as BaseController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiController extends BaseController
{

    use Helpers;

    public function errorResponse($code = 0, $message = null, $statusCode = 200)
    {
        throw new HttpException($statusCode, $message, null, [], $code);
    }
}
