<?php

namespace App\Rules\Animal;

use Illuminate\Contracts\Validation\Rule;

class ChangeChippingLocationRule implements Rule
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
        $animal = request()->route('animal')->load('visitedLocations');

        if (count($animal->visitedLocations) < 1) return true;

        if ($value == $animal->visitedLocations->first()->id) {
            return false;
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
        return 'Equal first visited location';
    }
}
