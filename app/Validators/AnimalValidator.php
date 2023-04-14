<?php

namespace App\Validators;

use App\Exceptions\BadRequestException;
use App\Models\Animal;
use App\Models\Location;
use App\Repositories\AnimalRepository;

class AnimalValidator
{
    private AnimalRepository $repository;

    /**
     * AccountValidator constructor.
     * @param \App\Repositories\AnimalRepository $repository
     */
    public function __construct(AnimalRepository $repository)
    {
        $this->repository = $repository;
    }

    public function animalDoestHaveVisitedLocationOrFail(Animal $animal)
    {
        if ($animal->visitedLocations->count()) {
            throw new BadRequestException();
        }
    }

    public function animalIsAliveOrFail(Animal $animal)
    {
        if ($animal->isDead()) {
            throw new BadRequestException();
        }
    }

    /**
     * Установка lifeStatus = “ALIVE”, если у животного lifeStatus = “DEAD”
     */
    public function animalIsNotDeadWhereNewStatusIsAliveOrFail(Animal $animal, $newStatus)
    {
        if ($newStatus == Animal::STATUS_ALIVE && $animal->isDead()) {
            throw new BadRequestException();
        }
    }

    /**
     * Новая точка чипирования совпадает с первой посещенной точкой локации
     */
    public function chippingLocationIsNotFirstVisitedPointOrFail(Animal $animal, Location $chippingLocation)
    {
        if ($animal->visitedLocations->count() && $animal->visitedLocations->first()->id == $chippingLocation->id) {
            throw new BadRequestException();
        }
    }
}
