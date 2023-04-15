<?php

namespace App\Validators;

use App\Exceptions\BadRequestException;
use App\Exceptions\ModelFieldExistsException;
use App\Repositories\AreaRepository;
use Location\Distance\Vincenty;
use Location\Intersection\Intersection;
use Location\Line;
use Location\Polygon;

class AreaValidator
{
    private AreaRepository $repository;

    /**
     * AreaValidator constructor.
     * @param \App\Repositories\AreaRepository $repository
     */
    public function __construct(AreaRepository $repository)
    {
        $this->repository = $repository;
    }

    public function areaNotExistSameNameOrFail($area, string $name)
    {
        $areaDB = $this->repository->findByName($name);

        if ($areaDB && $areaDB->id != $area->id) {
            throw new ModelFieldExistsException();
        }
    }

    /**
     * Все точки лежат на одной прямой
     */
    public function inNotLineOrFail(array $geoPoints)
    {
        $calculator = new Vincenty();

        $fullLine = new Line($geoPoints[0], last($geoPoints));
        $dist = $fullLine->getLength($calculator);

        $sumDist = 0;

        foreach ($geoPoints as $key => $point) {
            if (isset($geoPoints[$key + 1])) {
                $segmentLine = new Line($point, $geoPoints[$key + 1]);
                $sumDist += $segmentLine->getLength($calculator);
//                $isLine = $isLine || $intersection->intersects($fullLine, $segmentLine);
//                $isLine = $isLine || ($fullLine->intersectsLine($segmentLine));
            }
        }

        if (round($dist) == round($sumDist)) {
            throw new BadRequestException('area is line');
        }
    }

    /**
     * Границы новой зоны пересекаются между собой
     */
    public function borderNotCrossEachOtherOrFail(array $lines)
    {
        $chunks = collect($lines)->chunk(2);

        $prevBorder = null;

        foreach ($chunks as $chunk) {
            $border = $chunk->first();

            if (blank($prevBorder)) {
                $prevBorder =  $border;
            } else {
                $isIntersect = ($prevBorder->intersectsLine($border));

                if ($isIntersect && (count($chunk) > 1)) {
                    throw new BadRequestException('area borders cross each other');
                }
            }
        }
    }

    /**
     * Границы новой зоны пересекают границы уже существующей зоны
     */
    public function borderNotCrossOtherAreaOrFail(array $pointsCurrent, array $pointsOther)
    {

    }

    /**
     * Граница зоны находятся внутри границ существующей зоны
     */
    public function areaNotInsideOtherAreaOrFail(Polygon $polygonCurrent, Polygon $polygonOther)
    {
        $intersection = new Intersection();

//        if ($polygonCurrent->containsGeometry($polygonOther) || $polygonOther->containsGeometry($polygonCurrent)) {
//            throw new BadRequestException('area inside other area');
//        }

        if ($intersection->intersects($polygonCurrent, $polygonOther) || $intersection->intersects($polygonOther, $polygonCurrent)) {
            throw new BadRequestException('area inside other area');
        }
    }

    /**
     * Новая зона имеет дубликаты точек
     */
    public function areaDoesntHaveDuplicatePointsOrFail(array $areaPoints)
    {
        $groupsByCoords = collect($areaPoints)->groupBy(function (array $item, int $key) {
            return $item['longitude'] . '-' . $item['latitude'];
        });

        foreach ($groupsByCoords as $group) {
            if (count($group) > 1) {
                throw new BadRequestException('area has duplicate points');
            }
        }
    }

    /**
     * Зона, состоящая из таких точек, уже существует
     */
    public function areaDoesntHaveSamePointsOrFail(array $geoPointsCurrent, Polygon $polygonOtherArea)
    {
        $count = 0;

        foreach ($geoPointsCurrent as $geoPoint) {
            if ($polygonOtherArea->contains($geoPoint)) $count++;
        }

        if ($count == count($geoPointsCurrent)) {
            throw new ModelFieldExistsException('has same other area');
        }
    }
}
