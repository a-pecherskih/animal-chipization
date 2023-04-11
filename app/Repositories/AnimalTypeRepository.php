<?php

namespace App\Repositories;

use App\Models\AnimalType;

class AnimalTypeRepository
{
    public function findByIdOrFail(int $id): ?AnimalType
    {
        return AnimalType::findOrFail($id);
    }

    public function findByType(string $type): ?AnimalType
    {
        return AnimalType::firstWhere('type', $type);
    }

    public function create(array $data): AnimalType
    {
        return AnimalType::query()->create([
            'type' => $data['type'],
        ]);
    }

    public function update($animalType, array $data): AnimalType
    {
        $animalType->fill([
            'type' => $data['type'],
        ]);
        $animalType->save();

        return $animalType;
    }
}
