<?php

namespace App\Packages\Geometry;

interface Polygon {
    public function __construct(array $points);
    public function getBorders(): array;
}
