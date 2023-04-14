<?php

namespace App\Services\Animal;

use App\Repositories\Animal\TypeRepository;
use App\Repositories\AnimalRepository;
use App\Repositories\AnimalTypeRepository;
use App\Validators\Animal\TypeValidator;

class TypeService
{
    private AnimalRepository $animalRepository;
    private AnimalTypeRepository $animalTypeRepository;
    private TypeRepository $typeRepository;
    private TypeValidator $typeValidator;

    /**
     * TypeService constructor.
     * @param \App\Repositories\AnimalRepository $animalRepository
     * @param \App\Repositories\AnimalTypeRepository $animalTypeRepository
     * @param \App\Repositories\Animal\TypeRepository $typeRepository
     * @param \App\Validators\Animal\TypeValidator $typeValidator
     */
    public function __construct(
        AnimalRepository $animalRepository,
        AnimalTypeRepository $animalTypeRepository,
        TypeRepository $typeRepository,
        TypeValidator $typeValidator
    )
    {
        $this->animalRepository = $animalRepository;
        $this->animalTypeRepository = $animalTypeRepository;
        $this->typeRepository = $typeRepository;
        $this->typeValidator = $typeValidator;
    }


    public function addType(int $animalId, int $typeId)
    {
        $animal = $this->animalRepository->findByIdOrFail($animalId);
        $animalType = $this->animalTypeRepository->findByIdOrFail($typeId);

        $animal->types()->attach($animalType->id);
        return $animal->load('types');
    }

    public function changeType(int $animalId, array $data)
    {
        $animal = $this->animalRepository->findByIdOrFail($animalId, ['types']);
        $oldAnimalType = $this->animalTypeRepository->findByIdOrFail($data['oldTypeId']);
        $newAnimalType = $this->animalTypeRepository->findByIdOrFail($data['newTypeId']);

        $this->typeValidator->checkAnimalHasTypeOrFail($animal, $oldAnimalType);
        $this->typeValidator->checkAnimalAlreadyHasTypeOrFail($animal, $oldAnimalType);
        $this->typeValidator->checkAnimalAlreadyHasTypeOrFail($animal, $newAnimalType);

        $this->typeRepository->updateTypeOfAnimal($animal, $oldAnimalType, $newAnimalType);

        return $animal->load('types');
    }

    public function deleteType(int $animalId, int $typeId)
    {
        $animal = $this->animalRepository->findByIdOrFail($animalId, ['types']);
        $animalType = $this->animalTypeRepository->findByIdOrFail($typeId);

        $this->typeValidator->checkAnimalHasTypeOrFail($animal, $animalType);

        $this->typeRepository->deleteTypeFromAnimal($animal, $animalType);
    }
}
