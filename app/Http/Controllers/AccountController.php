<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\RegistrationRequest;
use App\Http\Requests\Account\SearchAccountsRequest;
use App\Http\Requests\Account\UpdateAccountRequest;
use App\Http\Resources\AccountResource;
use App\Models\User;
use App\Services\AccountService;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    public function registration(RegistrationRequest $request, AccountService $service)
    {
        $data = $request->validated();

        $user = $service->create($data);

        return response()->json(new AccountResource($user), Response::HTTP_CREATED);
    }

    public function show(User $user)
    {
        return response()->json(new AccountResource($user), Response::HTTP_OK);
    }

    public function search(SearchAccountsRequest $request, AccountService $service)
    {
        $data = $request->validated();

        $accounts = $service->search($data);

        return response()->json(AccountResource::collection($accounts), Response::HTTP_OK);
    }

    public function update(User $user, UpdateAccountRequest $request, AccountService $service)
    {
        $data = $request->validated();

        $user = $service->update($user, $data);

        return response()->json(new AccountResource($user), Response::HTTP_OK);
    }

    public function delete(User $user, AccountService $service)
    {
        $service->delete($user);

        return response()->json([], Response::HTTP_OK);
    }
}
