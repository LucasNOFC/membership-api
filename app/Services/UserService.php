<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Json;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserService
{
    public function register(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function delete(int $id): array
    {
        $user = User::findOrFail($id)->delete();

        return [
            'message' => 'Usuário deletado com sucesso',
            'user' => $user
        ];
    }

    public function edit(int $id, array $data): User
    {
        $user = User::FindOrFail($id);

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return $user->fresh();
    }

    public function login(array $credentials): array
    {
        if (!Auth::attempt($credentials)) {
            abort(401, 'Credenciais inválidas');
        }

        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'token' => $token,
            'user' => $user
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function getUsers(int $perPage = 10): array
    {
        $users = User::query()
            ->select('name', 'email', 'role', 'id')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return [
            'users' => $users
        ];
    }

    public function getUser(int $id): array 
    {
        $user = User::query()
        ->select('name', 'email', 'password', 'role')
        ->findOrFail($id);

        return [
            'user' => $user
        ];
    }
}
