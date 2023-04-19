<?php

namespace App\Http\Controllers;

use App\Http\Requests\Location\CreateLocationRequest;
use App\Http\Requests\Location\DeleteLocationRequest;
use App\Http\Requests\Location\GeohashLocationRequest;
use App\Http\Requests\Location\ShowLocationRequest;
use App\Http\Requests\Location\UpdateLocationRequest;
use App\Http\Resources\LocationResource;
use App\Services\LocationService;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class LocationController extends Controller
{
    private LocationService $service;

    /**
     * LocationController constructor.
     * @param \App\Services\LocationService $service
     */
    public function __construct(LocationService $service)
    {
        $this->service = $service;
    }

    public function show(int $id, ShowLocationRequest $request)
    {
        $location = $this->service->show($id);

        return response()->json(new LocationResource($location), Response::HTTP_OK);
    }

    public function create(CreateLocationRequest $request)
    {
        Gate::check('create-location', [self::class]);

        $location = $this->service->create($request->validated());

        return response()->json(new LocationResource($location), Response::HTTP_CREATED);
    }

    public function update(UpdateLocationRequest $request, int $id)
    {
        Gate::check('update-location', [self::class]);

        $location = $this->service->update($id, $request->validated());

        return response()->json(new LocationResource($location), Response::HTTP_OK);
    }

    public function delete(int $id, DeleteLocationRequest $request)
    {
        Gate::check('delete-location', [self::class]);

        $this->service->delete($id);

        return response()->json([], Response::HTTP_OK);
    }

    public function search(GeohashLocationRequest $request)
    {
        $location = $this->service->findByCoordinates($request->validated());
        return $location->id;
    }

    public function geohash(GeohashLocationRequest $request)
    {
        return $this->service->geohash($request->validated());
    }

    public function geohashV2(GeohashLocationRequest $request)
    {
        return $this->service->geohashV2($request->validated());
    }

    public function geohashV3(GeohashLocationRequest $request)
    {
        return $this->service->geohashV3($request->validated());
    }
}
