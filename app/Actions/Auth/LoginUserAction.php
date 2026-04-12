<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class LoginUserAction
{
    /**
     * @return array{user: User, token: string}
     */
    public function execute(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw new AuthenticationException('Credenciais inválidas.');
        }

        $token = $user->createToken($data['device_name'] ?? 'api')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }
}