<?php

namespace App\Http\Controllers;

use App\Http\Requests\Animal\CreateAnimalRequest;
use App\Http\Requests\Animal\DeleteAnimalRequest;
use App\Http\Requests\Animal\SearchAnimalRequest;
use App\Http\Requests\Animal\ShowAnimalRequest;
use App\Http\Requests\Animal\UpdateAnimalRequest;
use App\Http\Resources\AnimalResource;
use App\Repositories\AnimalRepository;
use App\Services\AnimalService;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AnimalController extends Controller
{
    private AnimalService $service;
    private AnimalRepository $repository;

    /**
     * AnimalController constructor.
     * @param \App\Services\AnimalService $service
     * @param \App\Repositories\AnimalRepository $repository
     */
    public function __construct(AnimalService $service, AnimalRepository $repository)
    {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function show(int $id, ShowAnimalRequest $request)
    {
        $animal = $this->repository->findByIdOrFail($id, ['types', 'visitedLocations']);

        return response()->json(new AnimalResource($animal), Response::HTTP_OK);
    }

    public function search(SearchAnimalRequest $request)
    {
        $accounts = $this->service->search($request->validated());

        return response()->json(AnimalResource::collection($accounts), Response::HTTP_OK);
    }

    public function create(CreateAnimalRequest $request)
    {
        Gate::check('create-animal', [self::class]);

        $animal = $this->service->create($request->validated());

        return response()->json(new AnimalResource($animal), Response::HTTP_CREATED);
    }

    public function update(int $id, UpdateAnimalRequest $request)
    {
        Gate::check('update-animal', [self::class]);

        $animal = $this->repository->findByIdOrFail($id,['types', 'visitedLocations']);

        $animal = $this->service->update($animal, $request->validated());

        return response()->json(new AnimalResource($animal), Response::HTTP_OK);
    }

    public function delete(int $id, DeleteAnimalRequest $request)
    {
        Gate::check('delete-animal', [self::class]);

        $animal = $this->repository->findByIdOrFail($id, ['visitedLocations']);

        $this->service->delete($animal);

        return response()->json([], Response::HTTP_OK);
    }
}
