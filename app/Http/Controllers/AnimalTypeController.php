<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnimalType\CreateAnimalTypeRequest;
use App\Http\Requests\AnimalType\UpdateAnimalTypeRequest;
use App\Http\Resources\AnimalTypeResource;
use App\Models\AnimalType;
use App\Services\AnimalTypeService;
use Symfony\Component\HttpFoundation\Response;

class AnimalTypeController extends Controller
{
    public function create(CreateAnimalTypeRequest $request, AnimalTypeService $service)
    {
        $data = $request->validated();

        $animalType = $service->create($data);

        return response()->json(new AnimalTypeResource($animalType), Response::HTTP_CREATED);
    }

    public function show(AnimalType $animalType)
    {
        return response()->json(new AnimalTypeResource($animalType), Response::HTTP_OK);
    }

    public function update(AnimalType $animalType, UpdateAnimalTypeRequest $request, AnimalTypeService $service)
    {
        $data = $request->validated();

        $animalType = $service->update($animalType, $data);

        return response()->json(new AnimalTypeResource($animalType), Response::HTTP_OK);
    }

    public function delete(AnimalType $animalType, AnimalTypeService $service)
    {
        $service->delete($animalType);

        return response()->json([], Response::HTTP_OK);
    }
}
