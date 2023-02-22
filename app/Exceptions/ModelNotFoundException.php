<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ModelNotFoundException extends Exception
{
    public function render($request)
    {
        abort(Response::HTTP_NOT_FOUND);
    }
}
