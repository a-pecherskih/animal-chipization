<?php

namespace App\Rules\Animal\Type;

use App\Exceptions\ModelFieldExistsException;
use Illuminate\Contracts\Validation\Rule;

class TypeAlreadyExistRule implements Rule
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
            throw new ModelFieldExistsException();
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
