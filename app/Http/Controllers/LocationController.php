<?php

namespace App\Http\Controllers;

use App\Http\Requests\Location\DeleteLocationRequest;
use App\Http\Requests\Location\CreateLocationRequest;
use App\Http\Requests\Location\GeohashLocationRequest;
use App\Http\Requests\Location\SearchLocationRequest;
use App\Http\Requests\Location\ShowLocationRequest;
use App\Http\Requests\Location\UpdateLocationRequest;
use App\Http\Resources\LocationResource;
use App\Repositories\LocationRepository;
use App\Services\LocationService;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class LocationController extends Controller
{
    private LocationService $service;
    private LocationRepository $repository;

    /**
     * LocationController constructor.
     * @param \App\Services\LocationService $service
     * @param \App\Repositories\LocationRepository $repository
     */
    public function __construct(LocationService $service, LocationRepository $repository)
    {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function show(int $id, ShowLocationRequest $request)
    {
        $location = $this->repository->findByIdOrFail($id);

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

        $location = $this->repository->findByIdOrFail($id);

        $location = $this->service->update($location, $request->validated());

        return response()->json(new LocationResource($location), Response::HTTP_OK);
    }

    public function delete(int $id, DeleteLocationRequest $request)
    {
        Gate::check('delete-location', [self::class]);

        $this->service->delete($id);

        return response()->json([], Response::HTTP_OK);
    }

    public function search(SearchLocationRequest $request)
    {
        $locations = $this->service->search($request->validated());
        return json_encode(1);
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
