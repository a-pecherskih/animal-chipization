<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class BadRequestException extends Exception
{
    public function render($request)
    {
        abort(Response::HTTP_BAD_REQUEST);
    }
}
