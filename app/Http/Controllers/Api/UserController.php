<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Traits\ResponseTrait;

class UserController extends Controller
{
    use ResponseTrait;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $models = $this->userService->getAll();
        return $this->success(UserResource::collection($models));
    }

    public function show($id)
    {
        $model = $this->userService->getById($id);
        return $this->success(new UserResource($model));
    }

    public function store(StoreUserRequest $request)
    {
        $model = $this->userService->create($request->validated());
        return $this->created(new UserResource($model));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $model = $this->userService->update($request->validated(), $id);
        return $this->updated(new UserResource($model));
    }

    public function destroy($id)
    {
        $this->userService->delete($id);
        return $this->noContent();
    }
}