<?php

namespace App\Http\Controllers\Animal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Animal\Type\AddTypeToAnimalRequest;
use App\Http\Requests\Animal\Type\DeleteAnimalTypeRequest;
use App\Http\Requests\Animal\Type\UpdateAnimalTypeRequest;
use App\Http\Resources\AnimalResource;
use App\Services\Animal\TypeService;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class TypeController extends Controller
{
    private TypeService $service;

    /**
     * TypeController constructor.
     * @param \App\Services\Animal\TypeService $service
     */
    public function __construct(TypeService $service)
    {
        $this->service = $service;
    }

    public function create(int $animalId, int $typeId, AddTypeToAnimalRequest $request)
    {
        Gate::check('add-type-to-animal', [self::class]);

        $animal = $this->service->addType($animalId, $typeId);

        return response()->json(new AnimalResource($animal), Response::HTTP_CREATED);
    }

    public function update(int $animalId, UpdateAnimalTypeRequest $request)
    {
        Gate::check('update-type-of-animal', [self::class]);

        $animal = $this->service->changeType($animalId, $request->validated());

        return response()->json(new AnimalResource($animal), Response::HTTP_OK);
    }

    public function delete(int $animalId, int $typeId, DeleteAnimalTypeRequest $request)
    {
        Gate::check('delete-type-from-animal', [self::class]);

        $this->service->deleteType($animalId, $typeId);

        return response()->json([], Response::HTTP_OK);
    }
}
