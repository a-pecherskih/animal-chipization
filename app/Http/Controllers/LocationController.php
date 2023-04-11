<?php

namespace App\Http\Controllers;

use App\Http\Requests\Location\DeleteLocationRequest;
use App\Http\Requests\Location\CreateLocationRequest;
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

    public function show(int $id)
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

        $location = $this->repository->findByIdOrFail($id);

        $this->service->delete($location);

        return response()->json([], Response::HTTP_OK);
    }
}
