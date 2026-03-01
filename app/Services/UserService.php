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

    public function delete(array $data): array
    {
        User::findOrFail($data['id'])->delete();

        return ['message' => 'Usuário deletado com sucesso'];
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

    public function getUser(int $perPage = 10): array
    {
        $users = User::query()
            ->select('name', 'email', 'role', 'id')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return [
            'users' => $users
        ];
    }
}
