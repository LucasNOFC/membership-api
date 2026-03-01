<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function getUsers(UserService $service) {
        $users = $service->getUser();

        return response()->json($users);
    }

    public function deleteUser(Request $request, UserService $service) {
        $data = $request->validate(['id' => ['required']]);

        $user = $service->delete($data);

        return response()->json($user);
    }

    public function register(Request $request, UserService $service)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
            'role' => ['required', 'in:admin,collaborator'],
        ]);

        $user = $service->register($data);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuário criado',
            'token' => $token,
            'user' => new UserResource($user),
        ], 201);
    }


    public function login(Request $request, UserService $service)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $result = $service->login($credentials);

        return response()->json([
            'token' => $result['token'],
            'user' => new UserResource($result['user'])
        ]);
    }

    public function logout(Request $request, UserService $service)
    {
        $service->logout($request->user());

        return response()->json([
            'message' => 'Logout realizado'
        ]);
    }
}
