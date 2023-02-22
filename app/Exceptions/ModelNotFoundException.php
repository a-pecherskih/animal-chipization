<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ModelNotFoundException extends Exception
{
    public function render($request)
    {
        return response()->json([], Response::HTTP_NOT_FOUND);
    }
}
