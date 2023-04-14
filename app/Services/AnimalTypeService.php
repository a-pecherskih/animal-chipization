<?php

namespace App\Services;

use App\Models\AnimalType;
use App\Repositories\AnimalTypeRepository;
use App\Validators\AnimalTypeValidator;

class AnimalTypeService
{
    private AnimalTypeRepository $repository;
    private AnimalTypeValidator $validator;

    /**
     * AccountService constructor.
     * @param \App\Repositories\AnimalTypeRepository $repository
     * @param \App\Validators\AnimalTypeValidator $validator
     */
    public function __construct(AnimalTypeRepository $repository, AnimalTypeValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function create(array $data)
    {
        $this->validator->checkNotExistAnimalTypeOrThrowEx($data['type']);

        return $this->repository->create($data);
    }

    public function update(AnimalType $animalType, array $data)
    {
        $this->validator->checkNotExistAnimalTypeOrThrowEx($data['type'], $animalType);

        return $this->repository->update($animalType, $data);
    }

    public function delete(AnimalType $animalType)
    {
        $this->validator->checkNotExistAnimalsWithTypeOrThrowEx($animalType);

        $this->repository->delete($animalType);
    }
}
