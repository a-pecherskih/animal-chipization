<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnimalType\CreateAnimalTypeRequest;
use App\Http\Requests\AnimalType\DeleteAnimalTypeRequest;
use App\Http\Requests\AnimalType\ShowAnimalTypeRequest;
use App\Http\Requests\AnimalType\UpdateAnimalTypeRequest;
use App\Http\Resources\AnimalTypeResource;
use App\Services\AnimalTypeService;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AnimalTypeController extends Controller
{
    private AnimalTypeService $service;

    /**
     * AnimalTypeController constructor.
     * @param \App\Services\AnimalTypeService $service
     */
    public function __construct(AnimalTypeService $service)
    {
        $this->service = $service;
    }

    public function show(int $id, ShowAnimalTypeRequest $request)
    {
        $animalType = $this->service->show($id);

        return response()->json(new AnimalTypeResource($animalType), Response::HTTP_OK);
    }

    public function create(CreateAnimalTypeRequest $request)
    {
        Gate::check('create-animal-type', [self::class]);

        $animalType = $this->service->create($request->validated());

        return response()->json(new AnimalTypeResource($animalType), Response::HTTP_CREATED);
    }

    public function update(int $id, UpdateAnimalTypeRequest $request)
    {
        Gate::check('update-animal-type', [self::class]);

        $animalType = $this->service->update($id, $request->validated());

        return response()->json(new AnimalTypeResource($animalType), Response::HTTP_OK);
    }

    public function delete(int $id, DeleteAnimalTypeRequest $request)
    {
        Gate::check('delete-animal-type', [self::class]);

        $this->service->delete($id);

        return response()->json([], Response::HTTP_OK);
    }
}
