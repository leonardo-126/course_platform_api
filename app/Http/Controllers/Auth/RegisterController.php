<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RegisterUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class RegisterController extends Controller
{
     public function __invoke(RegisterRequest $request, RegisterUserAction $action): JsonResponse
    {
        $user  = $action->execute($request->validated());
        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'user'  => new UserResource($user),
            'token' => $token,
        ], 201);
    }
}
