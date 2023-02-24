<?php

namespace App\Http\Controllers\Animal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Animal\Type\AddTypeToAnimalRequest;
use App\Http\Requests\Animal\Type\UpdateAnimalTypeRequest;
use App\Http\Requests\Animal\Type\DeleteAnimalTypeRequest;
use App\Http\Resources\AnimalResource;
use App\Models\Animal;
use App\Models\AnimalType;
use App\Services\Animal\TypeService;
use Symfony\Component\HttpFoundation\Response;

class TypeController extends Controller
{
    public function create(Animal $animal, AnimalType $type, AddTypeToAnimalRequest $request, TypeService $service)
    {
        $animal = $service->add($animal, $type);

        return response()->json(new AnimalResource($animal), Response::HTTP_CREATED);
    }

    public function update(Animal $animal, UpdateAnimalTypeRequest $request, TypeService $service)
    {
        $data = $request->validated();

        $animal = $service->update($animal, $data);

        return response()->json(new AnimalResource($animal), Response::HTTP_OK);
    }

    public function delete(Animal $animal, AnimalType $type, DeleteAnimalTypeRequest $request, TypeService $service)
    {
        $service->delete($animal, $type);

        return response()->json([], Response::HTTP_OK);
    }
}
