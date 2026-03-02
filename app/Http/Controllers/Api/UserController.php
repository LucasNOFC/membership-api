<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function getUsers(UserService $service)
    {
        $users = $service->getUsers();

        return response()->json($users);
    }

    public function getUser(int $id, UserService $service)
    {
        $user = $service->getUser($id);

        return response()->json($user);
    }

    public function deleteUser(int $id, UserService $service)
    {

        $user = $service->delete($id);

        return response()->json($user);
    }

    public function editUser(int $id, Request $request, UserService $service)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
            'password' => ['sometimes', 'min:8'],
            'role' => ['sometimes', 'in:admin,collaborator'],
        ]);

        $user = $service->edit($id, $data);

        return response()->json(
            ['message' => 'Usuário editado'],
            ['user' => $user],
        );
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
