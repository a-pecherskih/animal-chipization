<?php

namespace App\Http\Requests\Location;

use App\Http\Requests\BaseRequest;

class SearchLocationRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'latitude' => 'numeric|between:-90,90',
            'longitude' => 'numeric|between:-180,180',
        ];
    }
}
