<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UserNotCurrentException extends Exception
{
    public function render($request)
    {
        throw new HttpResponseException(response()->json([
            'errors' => 'Forbidden'
        ], Response::HTTP_FORBIDDEN));
    }
}
