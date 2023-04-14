<?php

namespace App\Validators\Animal;

use App\Exceptions\BadRequestException;
use App\Exceptions\ModelNotFoundException;
use App\Models\Animal;
use App\Models\AnimalLocation;
use App\Models\Location;

class VisitedLocationValidator
{
    /**
     * Животное находится в точке чипирования и никуда не перемещалось,
     * попытка добавить точку локации, равную точке чипирования.
     */
    public function pointIsChippingLocation(Animal $animal, Location $location)
    {
        $isChipping = ($animal->visitedLocations->count() == 1)
            && ($animal->chipping_location_id == $location->id)
            && ($location->id == $animal->visitedLocations->first()->id);

        if ($isChipping) {
            throw new BadRequestException();
        }
    }

    /**
     * Попытка добавить точку локации, в которой уже находится животное
     */
    public function animalIsNotAlreadyInThisPointOrFail(Animal $animal, Location $location)
    {
        $isLastPoint = $animal->visitedLocations->last()->id == $location->id;

        if ($isLastPoint) {
            throw new BadRequestException();
        }
    }

    /**
     * У животного нет объекта с информацией о посещенной точке локации с visitedLocationPointId.
     */
    public function animalHasVisitedPointOrFail(Animal $animal, AnimalLocation $visitedPoint)
    {
        foreach ($animal->visitedLocations as $visitedLocation) {
            if ($visitedLocation->pivot->id == $visitedPoint->id) return true;
        }

        throw new ModelNotFoundException();
    }

    /**
     * Обновление первой посещенной точки на точку чипирования
     */
    public function pointIsFirstAndNotChippingOrFail(Animal $animal, AnimalLocation $visitedPoint, Location $newPoint)
    {
        $isFirstAndChipping = $animal->visitedLocations->count()
            && $animal->visitedLocations->first()->id == $visitedPoint->visited_location_id
            && $newPoint->id == $animal->chipping_location_id;

        if ($isFirstAndChipping) {
            throw new BadRequestException();
        }
    }

    /**
     * Обновление точки на такую же точку
     */
    public function pointIsNotSameOrFail(AnimalLocation $visitedPoint, Location $newPoint)
    {
        $isSamePoint = $visitedPoint->visited_location_id == $newPoint->id;

        if ($isSamePoint) {
            throw new BadRequestException();
        }
    }

    /**
     * Обновление точки локации на точку, совпадающую со следующей и/или с предыдущей точками
     */
    public function pointIsNotPrevOrNextOrFail(Animal $animal, AnimalLocation $visitedPoint, Location $newPoint)
    {
        $isNextOrPrevPoint = false;

        foreach ($animal->visitedLocations as $key => $visitedLocation) {
            if ($visitedLocation->id == $visitedPoint->visited_location_id) {

                if (isset($visitedLocations[$key - 1])) {
                    if ($visitedLocations[$key - 1]->id == $newPoint->id) {
                        $isNextOrPrevPoint = true;
                        break;
                    }
                }

                if (isset($visitedLocations[$key + 1])) {
                    if ($visitedLocations[$key + 1]->id == $newPoint->id){
                        $isNextOrPrevPoint = true;
                        break;
                    }
                }

                break;
            }
        }

        if ($isNextOrPrevPoint) {
            throw new BadRequestException();
        }
    }
}
