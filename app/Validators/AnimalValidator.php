<?php

namespace App\Validators;

use App\Exceptions\BadRequestException;
use App\Models\Animal;
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

    public function checkAnimalDoestHaveVisitedLocationOrFail(Animal $animal)
    {
        if ($animal->visitedLocations->count()) {
            throw new BadRequestException();
        }
    }
}
