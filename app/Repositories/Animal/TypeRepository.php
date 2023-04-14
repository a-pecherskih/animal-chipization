<?php

namespace App\Repositories\Animal;

use App\Models\Animal;
use App\Models\AnimalAnimalType;
use App\Models\AnimalType;

class TypeRepository
{
    public function addTypeToAnimal(Animal $animal, AnimalType $newType): Animal
    {
        $animal->types()->attach($newType->id);

        return $animal->load('types');
    }

    public function updateTypeOfAnimal(Animal $animal, AnimalType $oldType, AnimalType $newType): Animal
    {
        AnimalAnimalType::query()
            ->where('animal_id', $animal->id)
            ->where('animal_type_id', $oldType->id)
            ->update([
                'animal_type_id' => $newType->id
            ]);

        return $animal->load('types');
    }

    public function deleteTypeFromAnimal(Animal $animal, AnimalType $animalType)
    {
        $animal->types()->detach($animalType->id);
    }
}
