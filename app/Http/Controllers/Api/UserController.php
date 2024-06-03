<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getAll();
        return UserResource::collection($users);
    }

    public function show($id)
    {
        $user = $this->userService->getById($id);
        return new UserResource($user);
    }

    public function store(UserRequest $request)
    {
        $user = $this->userService->create($request->all());
        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function update(UserRequest $request, $id)
    {
        $user = $this->userService->update($request->all(), $id);
        return new UserResource($user);
    }

    public function destroy($id)
    {
        $this->userService->delete($id);
        return response()->json(['message' => 'User deleted successfully'], 204);
    }
}
