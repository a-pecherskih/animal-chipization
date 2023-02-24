<?php

namespace App\Rules\Animal\VisitedLocation;

use App\Models\AnimalLocation;
use Illuminate\Contracts\Validation\Rule;

class CheckNewVisitedPoint implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $oldVisitedPoint = AnimalLocation::query()
            ->find(request()->input('visitedLocationPointId'));

        if ($oldVisitedPoint->id == $value) return false;

        $animal = request()->route('animal')->load('visitedLocations');

        $visitedLocations = $animal->visitedLocations->toArray();

        /**
         * Обновление точки локации на точку, совпадающую со следующей и/или с предыдущей точками
         */
        foreach ($visitedLocations as $key => $visitedLocation) {
            if ($visitedLocation['id'] == $oldVisitedPoint->visited_location_id) {

                if (isset($visitedLocations[$key - 1])) {
                    if ($visitedLocations[$key - 1]['id'] == $value) return false;
                }

                if (isset($visitedLocations[$key + 1])) {
                    if ($visitedLocations[$key + 1]['id'] == $value) return false;
                }

                break;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'New point is wrong';
    }
}