<?php

namespace App\Http\Controllers;

use App\Http\Requests\Animal\CreateAnimalRequest;
use App\Http\Requests\Animal\SearchAnimalRequest;
use App\Http\Requests\Animal\UpdateAnimalRequest;
use App\Http\Resources\AnimalResource;
use App\Models\Animal;
use App\Services\AnimalService;
use Symfony\Component\HttpFoundation\Response;

class AnimalController extends Controller
{
    public function search(SearchAnimalRequest $request, AnimalService $service)
    {
        $data = $request->validated();

        $accounts = $service->search($data);

        return response()->json(AnimalResource::collection($accounts), Response::HTTP_OK);
    }

    public function create(CreateAnimalRequest $request, AnimalService $service)
    {
        $data = $request->validated();

        $animal = $service->create($data);

        return response()->json(new AnimalResource($animal), Response::HTTP_CREATED);
    }

    public function show(Animal $animal)
    {
        return response()->json(new AnimalResource($animal), Response::HTTP_OK);
    }

    public function update(Animal $animal, UpdateAnimalRequest $request, AnimalService $service)
    {
        $data = $request->validated();

        $animal = $service->update($animal, $data);

        return response()->json(new AnimalResource($animal), Response::HTTP_OK);
    }

    public function delete(Animal $animal, AnimalService $service)
    {
        $service->delete($animal);

        return response()->json([], Response::HTTP_OK);
    }
}
