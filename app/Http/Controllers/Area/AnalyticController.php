<?php

namespace App\Http\Controllers\Area;

use App\Http\Controllers\Controller;
use App\Http\Requests\Area\Analytic\AnalyticRequest;
use App\Http\Resources\AnimalResource;
use App\Services\Area\AnalyticService;
use Symfony\Component\HttpFoundation\Response;

class AnalyticController extends Controller
{
    private AnalyticService $service;

    /**
     * AnalyticController constructor.
     * @param \App\Services\Area\AnalyticService $service
     */
    public function __construct(AnalyticService $service)
    {
        $this->service = $service;
    }

    public function analyze(int $areaId, AnalyticRequest $request)
    {
        $analysis = $this->service->analyze($areaId, $request->validated());

        return response()->json($analysis, Response::HTTP_OK);
    }
}
