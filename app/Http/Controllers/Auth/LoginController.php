<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LoginUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request, LoginUserAction $action): JsonResponse
    {
        ['user' => $user, 'token' => $token] = $action->execute($request->validated());

        return response()->json([
            'user'  => new UserResource($user),
            'token' => $token,
        ]);
    }
}
