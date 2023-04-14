<?php

namespace App\Repositories\Animal;

use App\Models\Animal;
use App\Models\AnimalLocation;
use App\Models\Location;
use Illuminate\Database\Eloquent\Builder;

class VisitedLocationRepository
{
    public function findByIdOrFail($id): AnimalLocation
    {
        return AnimalLocation::query()->findOrFail($id);
    }

    public function search(Animal $animal, array $filters)
    {
        $startDateTime = $filters['startDateTime'] ?? null;
        $endDateTime = $filters['endDateTime'] ?? null;
        $from = $filters['from'] ?? 0;
        $size = $filters['size'] ?? 10;

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

    public function addVisitedLocationToAnimal(Animal $animal, Location $location): AnimalLocation
    {
        return AnimalLocation::query()
            ->create([
                'animal_id' => $animal->id,
                'visited_location_id' => $location->id,
                'date_time' => now()
            ]);
    }

    public function updateVisitedLocationOfAnimal(AnimalLocation $animalLocation, Location $newLocation): AnimalLocation
    {
        $animalLocation->visited_location_id = $newLocation->id;
        $animalLocation->save();

        return $animalLocation;
    }

    public function deleteVisitedPoint(Animal $animal, AnimalLocation $visitedPoint)
    {
        $detachPointsIds = [$visitedPoint->id];

        /**
         * Если удаляется первая посещенная точка локации,
         * а вторая точка совпадает с точкой чипирования, то она удаляется автоматически
         */
        if ($animal->visitedLocations->count() > 2 && $animal->visitedLocations->first()->pivot->id == $visitedPoint->id) {
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
