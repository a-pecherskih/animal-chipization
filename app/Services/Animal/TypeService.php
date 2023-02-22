<?php

namespace App\Services\Animal;

use App\Models\Animal;
use App\Models\AnimalAnimalType;
use App\Models\AnimalType;

class TypeService
{
    public function add(Animal $animal, AnimalType $animalType)
    {
        $animal->types()->attach($animalType->id);
        return $animal->load('types');
    }

    public function update(Animal $animal, array $data)
    {
        AnimalAnimalType::query()
            ->where('animal_id', $animal->id)
            ->where('animal_type_id', $data['oldTypeId'])
            ->update([
                'animal_type_id' => $data['newTypeId']
            ]);

        return $animal->load('types');
    }

    public function delete(Animal $animal, AnimalType $animalType)
    {
        $animal->types()->detach($animalType->id);
    }
}
