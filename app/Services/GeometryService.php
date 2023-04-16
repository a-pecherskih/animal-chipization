<?php

namespace App\Services;

use Location\Coordinate;
use Location\Distance\Vincenty;
use Location\Line;
use Location\Polygon;
use Location\Utility\PointToLineDistance;

class GeometryService
{
    public function getPoint($latitude, $longitude)
    {
        return new Coordinate($latitude, $longitude);
    }

    public function getPoints(array $coordinates): array
    {
        $points = [];

        foreach ($coordinates as $coordinate)
        {
            $points[] = $this->getPoint($coordinate['latitude'], $coordinate['longitude']);
        }

        return $points;
    }

    public function getLine(Coordinate $point1, Coordinate $point2)
    {
        return new Line($point1, $point2);
    }

    public function getLines(array $geoPoints): array
    {
        $lines = [];

        foreach ($geoPoints as $key => $point)
        {
            if (isset($geoPoints[$key + 1])) {
                $lines[] = $this->getLine($point, $geoPoints[$key + 1]);
            }
        }

        return $lines;
    }

    public function getPolygonFromGeoPoints(array $geoPoints)
    {
        $polygon = new Polygon();
        $polygon->addPoints($geoPoints);
        return $polygon;
    }

    private function getCosSegmetLine(Coordinate $start, Coordinate $end)
    {
        if (!$start->hasSameLocation($end)) {
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
    public function isLine(array $points)
    {
        $cos = $this->getCosSegmetLine($points[0], $points[1]);

        for ($i = 2; $i < count($points); $i++) {
            if ($cos != $this->getCosSegmetLine($points[$i - 1], $points[$i])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Точка является начальной
     */
    private function isStartPoint(Line $start, Line $end)
    {
        return $start->getPoint1()->hasSameLocation($end->getPoint2());
    }

    /**
     * Ищем пересечение границ
     */
    public function bordersCrossedEachOther(array $lines)
    {
        $prevBorder = null;

        foreach ($lines as $line) {
            $border = $line;

            if (blank($prevBorder)) {
                $prevBorder = $border;
            } else {
                $isIntersect = ($prevBorder->intersectsLine($border));

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
    private function pointIsStartOrEndOfLine(Coordinate $point, Line $line): bool
    {
        $pointToLineDistanceCalculator = new PointToLineDistance(new Vincenty());
        return (int)$pointToLineDistanceCalculator->getDistance($point, $line) == 0;
    }

    /**
     * Количество совпадающих точек начала и конца
     */
    private function countCommonStartAndEndPointsOfLine(Line $line1, Line $line2)
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
     * Линии имеют одни и те же точки начала и конца
     */
    private function linesHaveSamePointsStartAndEnd(Line $line1, Line $line2)
    {
        $count = $this->countCommonStartAndEndPointsOfLine($line1, $line2);

        return $count == 2;
    }

    /**
     * Линии имеют общую начальную точку
     */
    public function linesHaveCommonStartOrEndPoint(Line $line1, Line $line2)
    {
        $count = $this->countCommonStartAndEndPointsOfLine($line1, $line2);

        return $count == 1;
    }

    /**
     * Полигоны имеют пересечения, но пересечения не является общей границей
     */
    public function areasHaveCrossBordersNotCommon(Polygon $polygon1, Polygon $polygon2)
    {
        $lines1 = $polygon1->getSegments();
        $lines2 = $polygon2->getSegments();

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
}
