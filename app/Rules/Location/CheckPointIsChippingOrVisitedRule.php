<?php

namespace App\Rules\Location;

use App\Models\Animal;
use Illuminate\Contracts\Validation\Rule;

class CheckPointIsChippingOrVisitedRule implements Rule
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
        $exist = Animal::query()
            ->where('chipping_location_id', $value)
            ->orWhereHas('visitedLocations', function ($q) use ($value) {
                $q->where('visited_location_id', $value);
            })->exists();

        return !$exist;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Point is already chipping or visited';
    }
}
