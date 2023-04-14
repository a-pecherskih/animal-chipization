<?php

namespace App\Validators\Animal;

use App\Exceptions\ModelFieldExistsException;
use App\Exceptions\ModelNotFoundException;
use App\Models\Animal;
use App\Models\AnimalType;
use App\Repositories\AccountRepository;

class TypeValidator
{
    private AccountRepository $repository;

    /**
     * AccountValidator constructor.
     * @param \App\Repositories\AccountRepository $repository
     */
    public function __construct(AccountRepository $repository)
    {
        $this->repository = $repository;
    }

    public function checkAnimalHasTypeOrFail(Animal $animal, AnimalType $animalType)
    {
        $existType = $animal->type->firstWhere('id', $animalType->id);

        if (blank($existType)) {
            throw new ModelNotFoundException();
        }

        return true;
    }

    public function checkAnimalAlreadyHasTypeOrFail(Animal $animal, AnimalType $animalType)
    {
        $existType = $animal->type->firstWhere('id', $animalType->id);

        if (!blank($existType)) {
            throw new ModelFieldExistsException();
        }

        return true;
    }
}
