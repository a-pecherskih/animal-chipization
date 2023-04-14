<?php

namespace App\Http\Controllers;

use App\Http\Requests\Animal\CreateAnimalRequest;
use App\Http\Requests\Animal\DeleteAnimalRequest;
use App\Http\Requests\Animal\SearchAnimalRequest;
use App\Http\Requests\Animal\ShowAnimalRequest;
use App\Http\Requests\Animal\UpdateAnimalRequest;
use App\Http\Resources\AnimalResource;
use App\Services\AnimalService;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AnimalController extends Controller
{
    private AnimalService $service;

    public function __construct(AnimalService $service)
    {
        $this->service = $service;
    }

    public function show(int $animalId, ShowAnimalRequest $request)
    {
        $animal = $this->service->show($animalId);

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

    public function update(int $animalId, UpdateAnimalRequest $request)
    {
        Gate::check('update-animal', [self::class]);

        $animal = $this->service->update($animalId, $request->validated());

        return response()->json(new AnimalResource($animal), Response::HTTP_OK);
    }

    public function delete(int $animalId, DeleteAnimalRequest $request)
    {
        Gate::check('delete-animal', [self::class]);

        $this->service->delete($animalId);

        return response()->json([], Response::HTTP_OK);
    }
}
