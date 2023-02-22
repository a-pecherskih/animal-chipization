<?php

namespace App\Rules\Animal;

use App\Models\Animal;
use Illuminate\Contracts\Validation\Rule;

class ChangeLifeStatusRule implements Rule
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
        $animal = request()->route('animal');

        if ($animal->life_status == Animal::STATUS_DEAD && $value == Animal::STATUS_ALIVE) {
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
        return 'Status cannot change';
    }
}
