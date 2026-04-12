<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RegisterUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request, RegisterUserAction $action): JsonResponse
    {
        $user = $action->execute($request->validated());

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'user' => new UserResource($user),
        ], 201);
    }
}
