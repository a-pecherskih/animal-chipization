<?php

namespace App\Services\Animal;

use App\Repositories\Animal\VisitedLocationRepository;
use App\Repositories\AnimalRepository;
use App\Repositories\LocationRepository;
use App\Validators\Animal\VisitedLocationValidator;
use App\Validators\AnimalValidator;

class VisitedLocationService
{
    private AnimalRepository $animalRepository;
    private LocationRepository $locationRepository;
    private VisitedLocationRepository $visitedLocationRepository;
    private AnimalValidator $animalValidator;
    private VisitedLocationValidator $visitedLocationValidator;

    /**
     * VisitedLocationService constructor.
     * @param \App\Repositories\AnimalRepository $animalRepository
     * @param \App\Repositories\LocationRepository $locationRepository
     * @param \App\Repositories\Animal\VisitedLocationRepository $visitedLocationRepository
     * @param \App\Validators\AnimalValidator $animalValidator
     * @param \App\Validators\Animal\VisitedLocationValidator $visitedLocationValidator
     */
    public function __construct(
        AnimalRepository $animalRepository,
        LocationRepository $locationRepository,
        VisitedLocationRepository $visitedLocationRepository,
        AnimalValidator $animalValidator,
        VisitedLocationValidator $visitedLocationValidator
    )
    {
        $this->animalRepository = $animalRepository;
        $this->locationRepository = $locationRepository;
        $this->visitedLocationRepository = $visitedLocationRepository;
        $this->animalValidator = $animalValidator;
        $this->visitedLocationValidator = $visitedLocationValidator;
    }

    public function search(int $animalId, array $data)
    {
        $animal = $this->animalRepository->findByIdOrFail($animalId);

        return $this->visitedLocationRepository->search($animal, $data);
    }

    public function addVisitedLocation(int $animalId, int $pointId)
    {
        $animal = $this->animalRepository->findByIdOrFail($animalId, ['visitedLocations']);
        $location = $this->locationRepository->findByIdOrFail($pointId);

        $this->animalValidator->animalIsAliveOrFail($animal);
        $this->visitedLocationValidator->pointIsChippingLocation($animal, $location);
        $this->visitedLocationValidator->animalIsNotAlreadyInThisPointOrFail($animal, $location);

        return $this->visitedLocationRepository->addVisitedLocationToAnimal($animal, $location);
    }

    public function updateVisitedLocation(int $animalId, array $data)
    {
        $animal = $this->animalRepository->findByIdOrFail($animalId, ['visitedLocations']);
        $visitedPoint = $this->visitedLocationRepository->findByIdOrFail($data['visitedLocationPointId']);
        $newPoint = $this->locationRepository->findByIdOrFail($data['locationPointId']);

        $this->visitedLocationValidator->animalHasVisitedPointOrFail($animal, $visitedPoint);
        $this->visitedLocationValidator->pointIsNotSameOrFail($visitedPoint, $newPoint);
        $this->visitedLocationValidator->pointIsFirstAndNotChippingOrFail($animal, $visitedPoint, $newPoint);
        $this->visitedLocationValidator->pointIsNotPrevOrNextOrFail($animal, $visitedPoint, $newPoint);

        return $this->visitedLocationRepository->updateVisitedLocationOfAnimal($visitedPoint, $newPoint);
    }

    public function deleteVisitedLocation(int $animalId, int $visitedPointId)
    {
        $animal = $this->animalRepository->findByIdOrFail($animalId, ['visitedLocations']);
        $visitedPoint = $this->visitedLocationRepository->findByIdOrFail($visitedPointId);

        $this->visitedLocationValidator->animalHasVisitedPointOrFail($animal, $visitedPoint);

        $this->visitedLocationRepository->deleteVisitedPoint($animal, $visitedPoint);
    }
}
