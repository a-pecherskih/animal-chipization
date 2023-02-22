<?php

namespace App\Rules\Animal\Type;

use App\Exceptions\ModelNotFoundException;
use Illuminate\Contracts\Validation\Rule;

class TypeNotExistRule implements Rule
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
        $animal = request()->route('animal')->load('types');

        if ($animal->types->firstWhere('id', $value)) {
            return true;
        }

        throw new ModelNotFoundException();
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
