<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UserNotCurrentException extends Exception
{
    public function render($request)
    {
        abort(Response::HTTP_FORBIDDEN);
    }
}
