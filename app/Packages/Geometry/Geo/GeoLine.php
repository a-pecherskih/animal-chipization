<?php

namespace App\Packages\Geometry\Geo;


use App\Packages\Geometry\Line;
use App\Packages\Geometry\Point;
use Location\Line as LocationLine;

class GeoLine implements Line
{
    private LocationLine $line;

    /**
     * GeoLine constructor.
     * @param \App\Packages\Geometry\Geo\GeoPoint|\App\Packages\Geometry\Point $point1
     * @param \App\Packages\Geometry\Geo\GeoPoint|\App\Packages\Geometry\Point $point2
     */
    public function __construct(GeoPoint|Point $point1, GeoPoint|Point $point2)
    {
        $this->line = new LocationLine($point1->getPoint(), $point2->getPoint());
    }

    /**
     * @return \App\Packages\Geometry\Geo\GeoPoint
     */
    public function getStart(): GeoPoint
    {
        $coordinate = $this->line->getPoint1();
        return (new GeoPoint($coordinate->getLat(), $coordinate->getLng()));
    }

    /**
     * @return \App\Packages\Geometry\Geo\GeoPoint
     */
    public function getEnd(): GeoPoint
    {
        $coordinate = $this->line->getPoint2();
        return (new GeoPoint($coordinate->getLat(), $coordinate->getLng()));
    }

    /**
     * @return \Location\Line
     */
    public function getLine(): LocationLine
    {
        return $this->line;
    }

    /**
     * @return \App\Packages\Geometry\Geo\GeoPoint[]
     */
    public function getPoints(): array
    {
        return [
            $this->getStart(),
            $this->getEnd()
        ];
    }
}
