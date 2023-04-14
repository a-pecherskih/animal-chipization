<?php

namespace App\Services;

use App\Models\Animal;
use App\Repositories\AccountRepository;
use App\Repositories\AnimalRepository;
use App\Repositories\LocationRepository;
use App\Validators\AnimalTypeValidator;
use App\Validators\AnimalValidator;
use Illuminate\Support\Collection;

class AnimalService
{
    private AnimalRepository $repository;
    private AccountRepository $accountRepository;
    private LocationRepository $locationRepository;
    private AnimalTypeValidator $animalTypeValidator;
    private AnimalValidator $validator;

    public function __construct(
        AnimalRepository $repository,
        AccountRepository $accountRepository,
        LocationRepository $locationRepository,
        AnimalTypeValidator $animalTypeValidator,
        AnimalValidator $validator)
    {
        $this->repository = $repository;
        $this->accountRepository = $accountRepository;
        $this->locationRepository = $locationRepository;
        $this->animalTypeValidator = $animalTypeValidator;
        $this->validator = $validator;
    }

    public function show(int $animalId): Animal
    {
        return $this->repository->findByIdOrFail($animalId, ['types', 'visitedLocations']);
    }

    public function search(array $data): Collection
    {
        return $this->repository->search($data);
    }

    public function create(array $data)
    {
        $chipper = $this->accountRepository->findByIdOrFail($data['chipperId']);
        $chippingLocationId = $this->locationRepository->findByIdOrFail($data['chippingLocationId']);

        $this->animalTypeValidator->hasAllTypesOrFail($data['animalTypes']);

        return $this->repository->create($data);
    }

    public function update(int $animalId, array $data)
    {
        $animal = $this->repository->findByIdOrFail($animalId, ['types', 'visitedLocations']);
        $chipper = $this->accountRepository->findByIdOrFail($data['chipperId']);
        $chippingLocation = $this->locationRepository->findByIdOrFail($data['chippingLocationId']);

        $this->validator->animalIsNotDeadWhereNewStatusIsAliveOrFail($animal, $data['lifeStatus']);
        $this->validator->chippingLocationIsNotFirstVisitedPointOrFail($animal, $chippingLocation);

        return $this->repository->update($animal, $data);
    }

    public function delete(int $animalId)
    {
        $animal = $this->repository->findByIdOrFail($animalId, ['visitedLocations']);

        $this->validator->animalDoestHaveVisitedLocationOrFail($animal);

        $this->repository->delete($animal);
    }
}
