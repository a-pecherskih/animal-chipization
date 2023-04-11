<?php

namespace App\Services;

use App\Models\Animal;
use App\Repositories\AnimalRepository;
use App\Validators\AnimalValidator;
use Illuminate\Support\Collection;

class AnimalService
{
    private AnimalRepository $repository;
    private AnimalRepository $validator;

    /**
     * AnimalService constructor.
     * @param \App\Repositories\AnimalRepository $repository
     * @param \App\Repositories\AnimalRepository $validator
     */
    public function __construct(AnimalRepository $repository, AnimalValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function search(array $data): Collection
    {
        return $this->repository->search($data);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(Animal $animal, array $data)
    {
        return $this->repository->update($animal, $data);
    }

    public function delete(Animal $animal)
    {
        $this->validator->checkAnimalDoestHaveVisitedLocationOrFail($animal);

        $animal->delete();
    }
}
