<?php

namespace App\Packages\Geometry;

interface Line {
    public function __construct(Point $point1, Point $point2);
    public function getStart();
    public function getEnd();
    public function getPoints(): array;
}
