<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnimalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'animalTypes' => $this->types->pluck('id')->toArray(),
            'weight' => round($this->weight, 2),
            'length' => round($this->length, 2),
            'height' => round($this->height, 2),
            'gender' => $this->gender,
            'lifeStatus' => $this->life_status,
            'chippingDateTime' => $this->chipping_date_time,
            'chipperId' => $this->chipper_id,
            'chippingLocationId' => $this->chipping_location_id,
            'visitedLocations' => $this->visitedLocations->pluck('id')->toArray(),
            'deathDateTime' => $this->death_date_time,
        ];
    }
}
