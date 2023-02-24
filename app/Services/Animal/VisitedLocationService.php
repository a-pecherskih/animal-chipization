<?php

namespace App\Services\Animal;

use App\Models\Animal;
use App\Models\AnimalLocation;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class VisitedLocationService
{
    public function search(Animal $animal, array $data)
    {
        $startDateTime = $data['startDateTime'] ?? null;
        $endDateTime = $data['endDateTime'] ?? null;
        $from = $data['from'] ?? 0;
        $size = $data['size'] ?? 10;

        return AnimalLocation::query()
            ->where('animal_id', $animal->id)
            ->when($startDateTime, function (Builder $q) use ($startDateTime) {
                $q->where('date_time', '>=', $startDateTime);
            })
            ->when($endDateTime, function (Builder $q) use ($endDateTime) {
                $q->where('date_time', '<=', $endDateTime);
            })
            ->offset($from)
            ->limit($size)
            ->orderBy('date_time')
            ->get();
    }

    public function add(Animal $animal, Location $location)
    {
        return AnimalLocation::query()
            ->create([
                'animal_id' => $animal->id,
                'visited_location_id' => $location->id,
                'date_time' => now()
            ]);
    }

    public function update(array $data)
    {
        AnimalLocation::query()
            ->where('id', $data['visitedLocationPointId'])
            ->update([
                'visited_location_id' => $data['locationPointId']
            ]);

        return AnimalLocation::query()->find($data['visitedLocationPointId']);
    }

    public function delete(Animal $animal, AnimalLocation $animalLocation)
    {
        $detachPointsIds = [$animalLocation->id];

        /**
         * Если удаляется первая посещенная точка локации,
         * а вторая точка совпадает с точкой чипирования, то она удаляется автоматически
         */
        if (count($animal->visitedLocations) == 2 && $animal->visitedLocations[0]->pivot->id == $animalLocation->id) {
            if ($animal->visitedLocations[1]->id == $animal->chipping_location_id) {
                $detachPointsIds[] = $animal->visitedLocations[1]->pivot->id;
            }
        }

        AnimalLocation::query()
            ->where('animal_id', $animal->id)
            ->whereIn('id', $detachPointsIds)
            ->delete();
    }
}
