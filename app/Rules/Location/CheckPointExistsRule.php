<?php

namespace App\Rules\Location;

use App\Models\Location;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

class CheckPointExistsRule implements Rule
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
        $updatedId = request()->route('location')->id ?? null;

        $location = Location::query()
            ->when($updatedId, function (Builder $q) use ($updatedId) {
                $q->where('id', '!=', $updatedId);
            })
            ->where([
                'latitude' => request()->input('latitude'),
                'longitude' => request()->input('longitude'),
            ])
            ->first();

        if ($location) {
            abort(409);
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
        return 'The validation error message.';
    }
}
