<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', RegisterController::class);
Route::post('/auth/login',    LoginController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', LogoutController::class);
    Route::get('/auth/me', fn (\Illuminate\Http\Request $r) => new \App\Http\Resources\UserResource($r->user()));
});