<?php

namespace App\Repositories;

use App\Models\AnimalType;
use Illuminate\Database\Eloquent\Collection;

class AnimalTypeRepository
{
    public function findByIdOrFail(int $id, $with = []): ?AnimalType
    {
        return AnimalType::query()
            ->when(!empty($with), function ($q) use ($with) {
                $q->with($with);
            })
            ->findOrFail($id);
    }

    public function findByIds(array $ids): Collection
    {
        return AnimalType::query()
            ->whereIn('id', $ids)
            ->get();
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

    public function update(AnimalType $animalType, array $data): AnimalType
    {
        $animalType->fill([
            'type' => $data['type'],
        ]);
        $animalType->save();

        return $animalType;
    }

    public function delete(AnimalType $animalType)
    {
        $animalType->delete();
    }
}
