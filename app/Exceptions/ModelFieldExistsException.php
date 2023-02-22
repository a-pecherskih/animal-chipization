<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ModelFieldExistsException extends Exception
{
    public function render($request)
    {
        abort(Response::HTTP_CONFLICT);
    }
}
