<?php

namespace App\Http\Requests\Area;

use App\Http\Requests\BaseRequest;

class CreateAreaRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'areaPoints' => 'required|array|min:3',
            'areaPoints.*.longitude' => 'required|numeric|between:-180,180',
            'areaPoints.*.latitude' => 'required|numeric|between:-90,90',
        ];
    }
}
