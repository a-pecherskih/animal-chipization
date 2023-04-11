<?php

namespace App\Repositories;

use App\Models\Animal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AnimalRepository
{
    public function findByIdOrFail(int $id, $with = []): Animal
    {
        return Animal::query()
            ->when(!empty($with), function ($q) use ($with) {
                $q->with($with);
            })
            ->findOrFail($id);
    }

    public function search(array $filters): Collection
    {
        $startDateTime = $filters['startDateTime'] ?? null;
        $endDateTime = $filters['endDateTime'] ?? null;
        $chipperId = $filters['chipperId'] ?? null;
        $chippingLocationId = $filters['chippingLocationId'] ?? null;
        $lifeStatus = $filters['lifeStatus'] ?? null;
        $gender = $filters['gender'] ?? null;
        $from = $filters['from'] ?? 0;
        $size = $filters['size'] ?? 10;

        return Animal::query()
            ->when($startDateTime, function (Builder $q) use ($startDateTime) {
                $q->where('chipping_date_time', '>=', $startDateTime);
            })
            ->when($endDateTime, function (Builder $q) use ($endDateTime) {
                $q->where('chipping_date_time', '<=', $endDateTime);
            })
            ->when($chipperId, function (Builder $q) use ($chipperId) {
                $q->where('chipper_id', $chipperId);
            })
            ->when($chippingLocationId, function (Builder $q) use ($chippingLocationId) {
                $q->where('chipping_location_id', $chippingLocationId);
            })
            ->when($lifeStatus, function (Builder $q) use ($lifeStatus) {
                $q->where('life_status', $lifeStatus);
            })
            ->when($gender, function (Builder $q) use ($gender) {
                $q->where('gender', $gender);
            })
            ->offset($from)
            ->limit($size)
            ->orderBy('id')
            ->with(['visitedLocations', 'types'])
            ->get();
    }

    public function create(array $data)
    {
        $animal = Animal::query()->create([
            'weight' => $data['weight'],
            'length' => $data['length'],
            'height' => $data['height'],
            'gender' => $data['gender'],
            'life_status' => Animal::STATUS_ALIVE,
            'chipper_id' => $data['chipperId'],
            'chipping_location_id' => $data['chippingLocationId'],
            'chipping_date_time' => now(),
        ]);

        $animal->types()->attach($data['animalTypes']);

        return $animal->load(['types', 'visitedLocations']);
    }

    public function update(Animal $animal, array $data)
    {
        $animal->fill([
            'weight' => $data['weight'],
            'length' => $data['length'],
            'height' => $data['height'],
            'gender' => $data['gender'],
            'life_status' => $data['lifeStatus'],
            'chipper_id' => $data['chipperId'],
            'chipping_location_id' => $data['chippingLocationId'],
        ]);

        if ($animal->life_status == Animal::STATUS_DEAD && is_null($animal->death_date_time)) {
            $animal->death_date_time = now();
        }

        $animal->save();

        return $animal;
    }
}
