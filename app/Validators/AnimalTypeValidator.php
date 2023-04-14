<?php

namespace App\Validators;

use App\Exceptions\BadRequestException;
use App\Exceptions\ModelFieldExistsException;
use App\Exceptions\ModelNotFoundException;
use App\Models\AnimalType;
use App\Repositories\AnimalTypeRepository;

class AnimalTypeValidator
{
    private AnimalTypeRepository $repository;

    /**
     * AccountValidator constructor.
     * @param \App\Repositories\AnimalTypeRepository $repository
     */
    public function __construct(AnimalTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function checkNotExistAnimalTypeOrThrowEx(string $type, ?AnimalType $ignoreType = null)
    {
        $type = $this->repository->findByType($type);

        if ($type && $ignoreType && ($type->id != $ignoreType->id)) return true;

        if ($type) {
            throw new ModelFieldExistsException();
        }
    }

    public function checkNotExistAnimalsWithTypeOrThrowEx(AnimalType $animalType)
    {
        if (count($animalType->animals) > 0) {
            throw new BadRequestException();
        }
    }

    public function hasAllTypesOrFail(array $ids)
    {
        $animalTypes = $this->repository->findByIds($ids);

        if ($animalTypes->count() != count($ids)) {
            throw new ModelNotFoundException();
        }
    }
}
