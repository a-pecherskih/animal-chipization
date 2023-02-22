<?php

namespace App\Services;

use App\Exceptions\BadRequestException;
use App\Models\AnimalType;

class AnimalTypeService
{
    public function create(array $data)
    {
        return AnimalType::query()->create([
            'type' => $data['type'],
        ]);
    }

    public function update(AnimalType $animalType, array $data)
    {
        $animalType->fill([
            'type' => $data['type'],
        ]);
        $animalType->save();

        return $animalType;
    }

    public function delete(AnimalType $animalType)
    {
        if (count($animalType->animals) > 0) {
            throw new BadRequestException();
        }

        $animalType->delete();
    }
}
