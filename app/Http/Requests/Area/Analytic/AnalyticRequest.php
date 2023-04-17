<?php

namespace App\Http\Requests\Area\Analytic;

use App\Http\Requests\BaseRequest;

class AnalyticRequest extends BaseRequest
{
    protected function prepareForValidation()
    {
        request()->merge(['areaId' => request()->route('areaId')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'areaId' => 'required|numeric|min:1',
            'startDate' => 'date|nullable|before:endDate',
            'endDate' => 'date|nullable',
        ];
    }
}
