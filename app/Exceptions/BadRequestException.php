<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class BadRequestException extends Exception
{
    public function render($request)
    {
        return response()->json([], Response::HTTP_BAD_REQUEST);
    }
}
