<?php

namespace App\Packages\Geometry\Geo;

use App\Packages\Geometry\Geometry;
use App\Packages\Geometry\Point;
use App\Packages\Geometry\Polygon;
use Location\Distance\Vincenty;
use Location\Intersection\Intersection;
use Location\Utility\PointToLineDistance;

class GeoGeometry implements Geometry
{
    /**
     * @param $latitude
     * @param $longitude
     * @return \App\Packages\Geometry\Geo\GeoPoint
     */
    public function getPointFromCoordinates($latitude, $longitude): GeoPoint
    {
        return new GeoPoint($latitude, $longitude);
    }

    /**
     * @param array $coordinates
     * @return array
     */
    public function getPointsFromCoordinates(array $coordinates): array
    {
        $points = [];

        foreach ($coordinates as $coordinate) {
            $points[] = $this->getPointFromCoordinates($coordinate['latitude'], $coordinate['longitude']);
        }

        return $points;
    }

    /**
     * @param array $points
     * @return \App\Packages\Geometry\Geo\GeoPolygon
     */
    public function getPolygonFromPoints(array $points): GeoPolygon
    {
        return new GeoPolygon($points);
    }

    private function getCosSegmetLine(GeoPoint $start, GeoPoint $end)
    {
        if (!$start->isSame($end)) {
            $sqrt = sqrt(
                ($end->getLat() - $start->getLat()) * ($end->getLat() - $start->getLat())
                + ($end->getLng() - $start->getLng()) * ($end->getLng() - $start->getLng())
            );

            return ($end->getLat() - $start->getLat()) / $sqrt;
        }

        return null;
    }


    /**
     * Точки образуют линию
     */
    public function isLine(array $points): bool
    {
        $cos = $this->getCosSegmetLine($points[0], $points[1]);

        for ($i = 2; $i < count($points); $i++) {
            if ($cos != $this->getCosSegmetLine($points[$i - 1], $points[$i])) {
                return false;
            }
        }

        return true;
    }

    private function pointInBorder(GeoLine $border, GeoPoint $point)
    {
        $points = [
            $border->getStart(),
            $point,
            $border->getEnd()
        ];

        if ($this->isLine($points)) {
            return true;
        }
    }

    private function pointInBorders(array $borders, Point $point)
    {
        foreach ($borders as $border) {
            $pointInBorder = $this->pointInBorder($border, $point);

            if ($pointInBorder) return true;
        }

        return false;
    }

    public function polygonHasPoint(GeoPolygon|Polygon $polygon, GeoPoint|Point $point): bool
    {
        $intersection = new Intersection();
        $inArea = $intersection->intersects($point->getPoint(), $polygon->getPolygon(), false);

        return $inArea || $this->pointInBorders($polygon->getBorders(), $point);
    }

    /**
     * Точка является начальной для двух линий
     */
    private function isStartPoint(GeoLine $start, GeoLine $end)
    {
        return $start->getStart()->isSame($end->getEnd());
    }

    /**
     * Ищем пересечение границ
     * @var \App\Packages\Geometry\Geo\GeoLine[] $lines
     */
    private function linesCrossedEachOther(array $lines): bool
    {
        $prevBorder = null;

        foreach ($lines as $line) {
            $border = $line;

            if (blank($prevBorder)) {
                $prevBorder = $border;
            } else {
                $isIntersect = ($prevBorder->getLine()->intersectsLine($border->getLine()));

                if ($isIntersect && !$this->isStartPoint($lines[0], $border)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Точка является началом или концом линии
     */
    private function pointIsStartOrEndOfLine(GeoPoint $point, GeoLine $line): bool
    {
        $pointToLineDistanceCalculator = new PointToLineDistance(new Vincenty());
        return (int)$pointToLineDistanceCalculator->getDistance($point->getPoint(), $line->getLine()) == 0;
    }

    /**
     * Количество совпадающих точек начала и конца
     */
    private function countCommonStartAndEndPointsOfLine(GeoLine $line1, GeoLine $line2)
    {
        $points1 = $line1->getPoints();
        $count = 0;

        foreach ($points1 as $point) {
            $has = $this->pointIsStartOrEndOfLine($point, $line2);
            $count += (int)$has;
        }

        return $count;
    }

    /**
     * Линии имеют общую начальную точку
     */
    private function linesHaveCommonStartOrEndPoint(GeoLine $line1, GeoLine $line2)
    {
        $count = $this->countCommonStartAndEndPointsOfLine($line1, $line2);

        return $count == 1;
    }

    /**
     * Линии имеют одни и те же точки начала и конца
     */
    private function linesHaveSamePointsStartAndEnd(GeoLine $line1, GeoLine $line2)
    {
        $count = $this->countCommonStartAndEndPointsOfLine($line1, $line2);

        return $count == 2;
    }

    public function polygonsHaveCrossBordersNotCommon(GeoPolygon|Polygon $polygon1, GeoPolygon|Polygon $polygon2): bool
    {
        $lines1 = $polygon1->getBorders();
        $lines2 = $polygon2->getBorders();

        foreach ($lines1 as $line1) {
            foreach ($lines2 as $line2) {
                if ($line1->intersectsLine($line2)) {
                    //Линии пересекаются, но не совпадают
                    if (!$this->linesHaveSamePointsStartAndEnd($line1, $line2) && !$this->linesHaveCommonStartOrEndPoint($line1, $line2)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param \App\Packages\Geometry\Geo\GeoPoint[] $points
     * @param \App\Packages\Geometry\Geo\GeoPolygon|\App\Packages\Geometry\Polygon $polygon
     * @return array
     */
    public function getPointsHasPolygon(array $points, GeoPolygon|Polygon $polygon): array
    {
        $pointsInPolygon = [];

        foreach ($points as $geoPoint) {
            if ($polygon->getPolygon()->contains($geoPoint->getPoint())) {
                $pointsInPolygon[] = $geoPoint;
            }
        }

        return $pointsInPolygon;
    }

    public function polygonIntersectsOtherPolygon(GeoPolygon|Polygon $polygon1, GeoPolygon|Polygon $polygon2): bool
    {
        $intersection = new Intersection();
        return $intersection->intersects($polygon1->getPolygon(), $polygon2->getPolygon())
            || $intersection->intersects($polygon2->getPolygon(), $polygon1->getPolygon());
    }

    public function polygonLinesCrossedEachOther(GeoPolygon|Polygon $polygon): bool
    {
        $lines = $polygon->getBorders();
        $chunks = collect($lines)->chunk(2);

        /** @var \App\Packages\Geometry\Geo\GeoLine[] $bordersPart1 */
        $bordersPart1 = $chunks->map(function ($chunk) {
            return $chunk->first();
        })->toArray();

        /** @var \App\Packages\Geometry\Geo\GeoLine[] $borders */
        $bordersPart2 = $chunks->map(function ($chunk) {
            return (count($chunk) == 1) ? null : $chunk->last();
        })->filter()->toArray();

        return $this->linesCrossedEachOther($bordersPart1) || $this->linesCrossedEachOther($bordersPart2);
    }
}
