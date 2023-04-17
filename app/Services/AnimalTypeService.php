<?php

namespace App\Services;

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

    public function show(int $animalTypeId)
    {
        return $this->repository->findByIdOrFail($animalTypeId);
    }

    public function create(array $data)
    {
        $this->validator->checkNotExistAnimalTypeOrThrowEx($data['type']);

        return $this->repository->create($data);
    }

    public function update(int $animalTypeId, array $data)
    {
        $animalType = $this->repository->findByIdOrFail($animalTypeId);

        $this->validator->checkNotExistAnimalTypeOrThrowEx($data['type'], $animalType);

        return $this->repository->update($animalType, $data);
    }

    public function delete(int $animalTypeId)
    {
        $animalType = $this->repository->findByIdOrFail($animalTypeId, ['animals']);

        $this->validator->checkNotExistAnimalsWithTypeOrThrowEx($animalType);

        $this->repository->delete($animalType);
    }
}
