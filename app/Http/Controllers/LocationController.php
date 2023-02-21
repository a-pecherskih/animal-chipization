<?php

namespace App\Http\Controllers;

use App\Http\Requests\Location\CreateOrUpdateLocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use App\Services\LocationService;
use Symfony\Component\HttpFoundation\Response;

class LocationController extends Controller
{
    public function create(CreateOrUpdateLocationRequest $request, LocationService $service)
    {
        $data = $request->validated();

        $location = $service->create($data);

        return response()->json(new LocationResource($location), Response::HTTP_CREATED);
    }

    public function show(Location $location)
    {
        return response()->json(new LocationResource($location), Response::HTTP_OK);
    }

    public function update(Location $location, CreateOrUpdateLocationRequest $request, LocationService $service)
    {
        $data = $request->validated();

        $location = $service->update($location, $data);

        return response()->json(new LocationResource($location), Response::HTTP_OK);
    }

    public function delete(Location $location, LocationService $service)
    {
        $service->delete($location);

        return response()->json([], Response::HTTP_OK);
    }
}
