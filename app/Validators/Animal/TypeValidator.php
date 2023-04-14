<?php

namespace App\Validators\Animal;

use App\Exceptions\ModelFieldExistsException;
use App\Exceptions\ModelNotFoundException;
use App\Models\Animal;
use App\Models\AnimalType;

class TypeValidator
{
    public function checkAnimalHasTypeOrFail(Animal $animal, AnimalType $animalType)
    {
        $existType = $animal->types->firstWhere('id', $animalType->id);

        if (blank($existType)) {
            throw new ModelNotFoundException();
        }

        return true;
    }

    public function checkAnimalAlreadyHasTypeOrFail(Animal $animal, AnimalType $animalType)
    {
        $existType = $animal->types->firstWhere('id', $animalType->id);

        if (!blank($existType)) {
            throw new ModelFieldExistsException();
        }

        return true;
    }
}
