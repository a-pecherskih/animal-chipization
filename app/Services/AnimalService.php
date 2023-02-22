<?php

namespace App\Services;

use App\Exceptions\BadRequestException;
use App\Models\Animal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AnimalService
{
    public function search(array $data): Collection
    {
        $startDateTime = $data['startDateTime'] ?? null;
        $endDateTime = $data['endDateTime'] ?? null;
        $chipperId = $data['chipperId'] ?? null;
        $chippingLocationId = $data['chippingLocationId'] ?? null;
        $lifeStatus = $data['lifeStatus'] ?? null;
        $gender = $data['gender'] ?? null;
        $from = $data['from'] ?? 0;
        $size = $data['size'] ?? 10;

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

        return $animal;
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
        ]);

        if ($animal->life_status == Animal::STATUS_DEAD && is_null($animal->death_date_time)) {
            $animal->death_date_time = now();
        }

        $animal->save();

        return $animal;
    }

    public function delete(Animal $animal)
    {
        if (count($animal->visitedLocations) > 0) {
            throw new BadRequestException();
        }

        $animal->delete();
    }
}
