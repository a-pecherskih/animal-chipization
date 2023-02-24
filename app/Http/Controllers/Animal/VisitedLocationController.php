<?php

namespace App\Http\Controllers\Animal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Animal\VisitedLocation\AddVisitedLocationToAnimalRequest;
use App\Http\Requests\Animal\VisitedLocation\DeleteVisitedLocationRequest;
use App\Http\Requests\Animal\VisitedLocation\SearchVisitedLocationsRequest;
use App\Http\Requests\Animal\VisitedLocation\UpdateVisitedLocationPointRequest;
use App\Http\Resources\VisitedLocationResource;
use App\Models\Animal;
use App\Models\AnimalLocation;
use App\Models\Location;
use App\Services\Animal\VisitedLocationService;
use Symfony\Component\HttpFoundation\Response;

class VisitedLocationController extends Controller
{
    public function search(Animal $animal, SearchVisitedLocationsRequest $request, VisitedLocationService $service)
    {
        $data = $request->validated();

        $accounts = $service->search($animal, $data);

        return response()->json(VisitedLocationResource::collection($accounts), Response::HTTP_OK);
    }

    public function create(Animal $animal, Location $location, AddVisitedLocationToAnimalRequest $request, VisitedLocationService $service)
    {
        $visitedLocation = $service->add($animal, $location);

        return response()->json(new VisitedLocationResource($visitedLocation), Response::HTTP_CREATED);
    }

    public function update(Animal $animal, UpdateVisitedLocationPointRequest $request, VisitedLocationService $service)
    {
        $data = $request->validated();

        $visitedLocation = $service->update($data);

        return response()->json(new VisitedLocationResource($visitedLocation), Response::HTTP_OK);
    }

    public function delete(Animal $animal, AnimalLocation $animalLocation, DeleteVisitedLocationRequest $request, VisitedLocationService $service)
    {
        $service->delete($animal, $animalLocation);

        return response()->json([], Response::HTTP_OK);
    }
}
