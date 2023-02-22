<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ModelFieldExistsException extends Exception
{
    public function render($request)
    {
        return response()->json([], Response::HTTP_CONFLICT);
    }
}
