<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\DeleteAccountRequest;
use App\Http\Requests\Account\RegistrationRequest;
use App\Http\Requests\Account\SearchAccountsRequest;
use App\Http\Requests\Account\ShowAccountRequest;
use App\Http\Requests\Account\StoreAccountRequest;
use App\Http\Requests\Account\UpdateAccountRequest;
use App\Http\Resources\AccountResource;
use App\Repositories\AccountRepository;
use App\Services\AccountService;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    private AccountService $service;
    private AccountRepository $repository;

    /**
     * AccountController constructor.
     * @param \App\Services\AccountService $service
     * @param \App\Repositories\AccountRepository $repository
     */
    public function __construct(AccountService $service, AccountRepository $repository)
    {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function registration(RegistrationRequest $request)
    {
        $user = $this->service->register($request->validated());

        return response()->json(new AccountResource($user), Response::HTTP_CREATED);
    }

    public function show(ShowAccountRequest $request, int $id)
    {
        $user = $this->repository->findById($id);

        Gate::check('get-account', [self::class, $user]);

        return response()->json(new AccountResource($user), Response::HTTP_OK);
    }

    public function search(SearchAccountsRequest $request)
    {
        Gate::check('search-account', [self::class]);

        $accounts = $this->service->search($request->validated());

        return response()->json(AccountResource::collection($accounts), Response::HTTP_OK);
    }

    public function create(StoreAccountRequest $request)
    {
        Gate::check('create-account', [self::class]);

        $user = $this->service->create($request->validated());

        return response()->json(new AccountResource($user), Response::HTTP_CREATED);
    }

    public function update(int $id, UpdateAccountRequest $request)
    {
        $user = $this->repository->findById($id);

        Gate::check('update-account', [self::class, $user]);

        $user = $this->service->update($user, $request->validated());

        return response()->json(new AccountResource($user), Response::HTTP_OK);
    }

    public function delete(DeleteAccountRequest $request, int $id)
    {
        $user = $this->repository->findById($id, ['role', 'animals']);

        Gate::check('delete-account', [self::class, $user]);

        $this->service->delete($user);

        return response()->json([], Response::HTTP_OK);
    }
}
