<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnimalType\CreateAnimalTypeRequest;
use App\Http\Requests\AnimalType\DeleteAnimalTypeRequest;
use App\Http\Requests\AnimalType\ShowAnimalTypeRequest;
use App\Http\Requests\AnimalType\UpdateAnimalTypeRequest;
use App\Http\Resources\AnimalTypeResource;
use App\Repositories\AnimalTypeRepository;
use App\Services\AnimalTypeService;
use Symfony\Component\HttpFoundation\Response;

class AnimalTypeController extends Controller
{
    private AnimalTypeService $service;
    private AnimalTypeRepository $repository;

    /**
     * AnimalTypeController constructor.
     * @param \App\Services\AnimalTypeService $service
     * @param \App\Repositories\AnimalTypeRepository $repository
     */
    public function __construct(AnimalTypeService $service, AnimalTypeRepository $repository)
    {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function show(int $id, ShowAnimalTypeRequest $request)
    {
        $animalType = $this->repository->findByIdOrFail($id);

        return response()->json(new AnimalTypeResource($animalType), Response::HTTP_OK);
    }

    public function create(CreateAnimalTypeRequest $request)
    {
        $animalType = $this->service->create($request->validated());

        return response()->json(new AnimalTypeResource($animalType), Response::HTTP_CREATED);
    }

    public function update(int $id, UpdateAnimalTypeRequest $request)
    {
        $animalType = $this->repository->findByIdOrFail($id);

        $animalType = $this->service->update($animalType, $request->validated());

        return response()->json(new AnimalTypeResource($animalType), Response::HTTP_OK);
    }

    public function delete(int $id, DeleteAnimalTypeRequest $request)
    {
        $animalType = $this->repository->findByIdOrFail($id);

        $this->service->delete($animalType);

        return response()->json([], Response::HTTP_OK);
    }
}
