<?php

namespace App\Repositories;

use App\Models\Area;

class AreaRepository
{
    public function findByIdOrFail(int $id): ?Area
    {
        return Area::query()->findOrFail($id);
    }

    public function findById(int $id): ?Area
    {
        return Area::query()->find($id);
    }

    public function create(array $data): Area
    {
        return Area::query()->create([
           'name' => $data['name'],
           'points' => $data['areaPoints']
        ]);
    }

    public function update(Area $area, array $data): Area
    {
        $area->fill([
            'name' => $data['name'],
            'points' => $data['areaPoints'],
        ]);
        $area->save();

        return $area;
    }

    public function delete(Area $area)
    {
        $area->delete();
    }
}
