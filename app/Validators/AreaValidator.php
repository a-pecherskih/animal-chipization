<?php

namespace App\Validators;

use App\Repositories\AreaRepository;

class AreaValidator
{
    private AreaRepository $repository;

    /**
     * AccountValidator constructor.
     * @param \App\Repositories\AreaRepository $repository
     */
    public function __construct(AreaRepository $repository)
    {
        $this->repository = $repository;
    }


}
