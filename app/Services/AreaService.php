<?php

namespace App\Services;

use App\Models\Area;
use App\Repositories\AreaRepository;
use App\Validators\AreaValidator;

class AreaService
{
    private AreaRepository $repository;
    private AreaValidator $validator;

    /**
     * AreaService constructor.
     * @param \App\Repositories\AreaRepository $repository
     * @param \App\Validators\AreaValidator $validator
     */
    public function __construct(AreaRepository $repository, AreaValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(Area $area, array $data)
    {
        return $this->repository->update($area, $data);
    }

    public function delete(Area $area)
    {
        $this->repository->delete($area);
    }
}
