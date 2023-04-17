<?php

namespace App\Services\Area;

use App\Models\Animal;
use App\Repositories\AnimalRepository;
use App\Repositories\AreaRepository;
use App\Services\GeometryService;
use Carbon\Carbon;
use Location\Coordinate;
use Location\Intersection\Intersection;
use Location\Line;
use Location\Polygon;

class AnalyticService
{
    private AreaRepository $areaRepository;
    private AnimalRepository $animalRepository;
    private GeometryService $geometryService;
    private Carbon $startDate;
    private Carbon $endDate;

    /**
     * AnalyticService constructor.
     * @param \App\Repositories\AreaRepository $areaRepository
     * @param \App\Repositories\AnimalRepository $animalRepository
     * @param \App\Services\GeometryService $geometryService
     */
    public function __construct(AreaRepository $areaRepository, AnimalRepository $animalRepository, GeometryService $geometryService)
    {
        $this->areaRepository = $areaRepository;
        $this->animalRepository = $animalRepository;
        $this->geometryService = $geometryService;
    }

    public function analyze($areaId, array $filters)
    {
        $area = $this->areaRepository->findByIdOrFail($areaId);
        $animals = $this->animalRepository->all(['visitedLocations', 'chippingLocation', 'types']);
        $this->startDate = isset($filters['startDate']) ? Carbon::parse($filters['startDate']) : now()->subYears(100);
        $this->endDate = isset($filters['endDate']) ? Carbon::parse($filters['endDate']) : now();

        $points = $this->geometryService->getPoints($area->points);
        $polygon = $this->geometryService->getPolygonFromGeoPoints($points);

        $quantityByType = [];


        $totalAnimals = [];
        $totalAnimalsArrived = [];
        $totalAnimalsGone = [];

        /** @var Animal $animal */
        foreach ($animals as $animal) {
            $animalInArea = $this->animalInArea($animal, $polygon);
            $animalIsArrived = $this->animalArrivedArea($animal, $polygon);
            $animalGoneArea = $this->animalGoneArea($animal, $polygon);

            $animalId = $animal->id;

            foreach ($animal->types as $type) {
                $typeId = $type->id;

                if (!isset($quantityByType[$typeId])) {
                    $quantityByType[$typeId] = [
                        'animalType' => $type->type,
                        'animalTypeId' => $typeId,
                        'quantityAnimals' => [],
                        'animalsArrived' => [],
                        'animalsGone' => [],
                    ];
                }

                if ($animalInArea) {
                    $quantityByType[$typeId]['quantityAnimals'][] = $animalId;
                }
                if ($animalIsArrived) {
                    $quantityByType[$typeId]['animalsArrived'][] = $animalId;
                }
                if ($animalGoneArea) {
                    $quantityByType[$typeId]['animalsGone'][] = $animalId;
                }
            }

            if ($animalInArea) {
                $totalAnimals[] = $animalId;
            }
            if ($animalIsArrived) {
                $totalAnimalsArrived[] = $animalId;
            }
            if ($animalGoneArea) {
                $totalAnimalsGone[] = $animalId;
            }
        }

        $result = [
            'totalQuantityAnimals' => count(array_unique($totalAnimals)),
            'totalAnimalsArrived' => count(array_unique($totalAnimalsArrived)),
            'totalAnimalsGone' => count(array_unique($totalAnimalsGone)),
            'animalsAnalytics' => []
        ];

        foreach ($quantityByType as $typeId => $quantitiesByType) {
            $result['animalsAnalytics'][] = [
                'animalType' => $quantitiesByType['animalType'],
                'animalTypeId' => $quantitiesByType['animalTypeId'],
                'quantityAnimals' => count(array_unique($quantitiesByType['quantityAnimals'])),
                'animalsArrived' => count(array_unique($quantitiesByType['animalsArrived'])),
                'animalsGone' => count(array_unique($quantitiesByType['animalsGone'])),
            ];
        }

        return $result;
    }

    private function dateBetweenTheseDates(Carbon|string $date)
    {
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        return $date->between($this->startDate, $this->endDate);
    }

    private function chippingLocationIsLastVisitedPoint($chippingLocation, $visitedLocations)
    {
        return ($visitedLocations->isEmpty() || ($visitedLocations->last()->id == $chippingLocation->id));
    }

    private function pointInBorder(Line $border, Coordinate $point)
    {
        $points = [
            $border->getPoint1(),
            $point,
            $border->getPoint2()
        ];

        if ($this->geometryService->isLine($points)) {
            return true;
        }
    }

    private function pointInBorders(array $borders, Coordinate $point)
    {
        foreach ($borders as $border) {
            $pointInBorder = $this->pointInBorder($border, $point);

            if ($pointInBorder) return true;
        }

        return false;
    }

    private function polygonHasPoint(Polygon $polygon, Coordinate $coordinate)
    {
        $intersection = new Intersection();
        $inArea = $intersection->intersects($coordinate, $polygon, false);

        return $inArea || $this->pointInBorders($polygon->getSegments(), $coordinate);
    }

    /**
     * Животное в зоне в указанный интервал времени
     * (Животное было чипировано в зоне и осталось в зоне)
     * (Последняя точка посещения у животного в зоне)
     */
    private function animalInArea(Animal $animal, Polygon $polygon)
    {
        $chippingLocation = $animal->chippingLocation;
        $geoPointChippingLocation = new Coordinate($chippingLocation->latitude, $chippingLocation->longitude);

        if ($this->chippingLocationIsLastVisitedPoint($chippingLocation, $animal->visitedLocations) && $this->polygonHasPoint($polygon, $geoPointChippingLocation)) {
            if ($this->dateBetweenTheseDates($animal->chipping_date_time)) {
                return true;
            }
        }

        if ($animal->visitedLocations->isEmpty()) return false;

        $lastVisitedLocation = $animal->visitedLocations->last();
        $geoPointLastVisitedLocation = new Coordinate($lastVisitedLocation->latitude, $lastVisitedLocation->longitude);

        if ($this->polygonHasPoint($polygon, $geoPointLastVisitedLocation)) {
            if ($this->dateBetweenTheseDates($lastVisitedLocation->pivot->date_time)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Животное прибыло в зону в указанный интервал времени
     * (Последняя точка посещения у животного в зоне)
     */
    private function animalArrivedArea(Animal $animal, Polygon $polygon)
    {
        if ($animal->visitedLocations->isEmpty()) return false;

        $lastVisitedLocation = $animal->visitedLocations->last();
        $geoPointLastVisitedLocation = new Coordinate($lastVisitedLocation->latitude, $lastVisitedLocation->longitude);

        if ($this->polygonHasPoint($polygon, $geoPointLastVisitedLocation)) {
            if ($this->dateBetweenTheseDates($lastVisitedLocation->pivot->date_time)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Животное покинуло зону в указанный интервал времени
     * (Животное было чипировано в зоне, а затем покинуло зону)
     * (Животное посетило зону, а затем покинуло зону)
     */
    private function animalGoneArea(Animal $animal, Polygon $polygon)
    {
        $chippingLocation = $animal->chippingLocation;
        $geoPointChippingLocation = new Coordinate($chippingLocation->latitude, $chippingLocation->longitude);

        if (!$this->chippingLocationIsLastVisitedPoint($chippingLocation, $animal->visitedLocations) && $this->polygonHasPoint($polygon, $geoPointChippingLocation)) {
            if ($this->dateBetweenTheseDates($animal->chipping_date_time)) {
                return true;
            }
        }

        if ($animal->visitedLocations->isEmpty()) return false;

        $lastVisitedLocation = $animal->visitedLocations->last();

        foreach ($animal->visitedLocations as $visitedLocation) {
            $geoPointLastVisitedLocation = new Coordinate($visitedLocation->latitude, $visitedLocation->longitude);
            if ($this->polygonHasPoint($polygon, $geoPointLastVisitedLocation) && $visitedLocation->id != $lastVisitedLocation->id) {

                if ($this->dateBetweenTheseDates($visitedLocation->pivot->date_time)) {
                    return true;
                }

            }
        }

        return false;
    }
}
