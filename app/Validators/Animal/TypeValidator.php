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

    /**
     * Животное с animalId уже имеет типы с oldTypeId и newTypeId
     */
    public function animalDoestHaveNewTypeOrFail(Animal $animal, AnimalType $newType)
    {
        $existNewType = $animal->types->firstWhere('id', $newType->id);

        if ($existNewType) {
            throw new ModelFieldExistsException();
        }

        return true;
    }
}
