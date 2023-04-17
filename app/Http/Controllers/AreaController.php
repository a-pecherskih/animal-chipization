<?php

namespace App\Http\Controllers;

use App\Http\Requests\Area\CreateAreaRequest;
use App\Http\Requests\Area\DeleteAreaRequest;
use App\Http\Requests\Area\ShowAreaRequest;
use App\Http\Requests\Area\UpdateAreaRequest;
use App\Http\Resources\AreaResource;
use App\Services\AreaService;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AreaController extends Controller
{
    private AreaService $service;

    /**
     * AreaController constructor.
     * @param \App\Services\AreaService $service
     */
    public function __construct(AreaService $service)
    {
        $this->service = $service;
    }

    public function show(int $id, ShowAreaRequest $request)
    {
        $area = $this->service->show($id);

        return response()->json(new AreaResource($area), Response::HTTP_OK);
    }

    public function create(CreateAreaRequest $request)
    {
        Gate::check('create-area', [self::class]);

        $area = $this->service->create($request->validated());

        return response()->json(new AreaResource($area), Response::HTTP_CREATED);
    }

    public function update(int $id, UpdateAreaRequest $request)
    {
        Gate::check('update-area', [self::class]);

        $area = $this->service->update($id, $request->validated());

        return response()->json(new AreaResource($area), Response::HTTP_OK);
    }

    public function delete(int $id, DeleteAreaRequest $request)
    {
        Gate::check('delete-area', [self::class]);

        $this->service->delete($id);

        return response()->json([], Response::HTTP_OK);
    }
}
