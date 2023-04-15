<?php

namespace App\Http\Controllers\Animal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Animal\VisitedLocation\AddVisitedLocationToAnimalRequest;
use App\Http\Requests\Animal\VisitedLocation\DeleteVisitedLocationRequest;
use App\Http\Requests\Animal\VisitedLocation\SearchVisitedLocationsRequest;
use App\Http\Requests\Animal\VisitedLocation\UpdateVisitedLocationPointRequest;
use App\Http\Resources\VisitedLocationResource;
use App\Services\Animal\VisitedLocationService;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class VisitedLocationController extends Controller
{
    private VisitedLocationService $service;

    /**
     * VisitedLocationController constructor.
     * @param \App\Services\Animal\VisitedLocationService $service
     */
    public function __construct(VisitedLocationService $service)
    {
        $this->service = $service;
    }

    public function search(int $animalId, SearchVisitedLocationsRequest $request)
    {
        $accounts = $this->service->search($animalId, $request->validated());

        return response()->json(VisitedLocationResource::collection($accounts), Response::HTTP_OK);
    }

    public function create(int $animalId, int $pointId, AddVisitedLocationToAnimalRequest $request)
    {
        Gate::check('add-visited-point-to-animal', [self::class]);

        $visitedLocation = $this->service->addVisitedLocation($animalId, $pointId);

        return response()->json(new VisitedLocationResource($visitedLocation), Response::HTTP_CREATED);
    }

    public function update(int $animalId, UpdateVisitedLocationPointRequest $request)
    {
        Gate::check('update-visited-point-of-animal', [self::class]);

        $visitedLocation = $this->service->updateVisitedLocation($animalId, $request->validated());

        return response()->json(new VisitedLocationResource($visitedLocation), Response::HTTP_OK);
    }

    public function delete(int $animalId, int $animalLocationId, DeleteVisitedLocationRequest $request)
    {
        Gate::check('delete-visited-point-from-animal', [self::class]);

        $this->service->deleteVisitedLocation($animal, $animalLocation);

        return response()->json([], Response::HTTP_OK);
    }
}
