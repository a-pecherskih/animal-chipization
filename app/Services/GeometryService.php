<?php

namespace App\Services;

use Location\Coordinate;
use Location\Line;
use Location\Polygon;

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
}
