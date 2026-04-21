<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\CourseAuthor\CourseAuthorController;
use App\Models\Course;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', RegisterController::class);
Route::post('/auth/login',    LoginController::class);
Route::get('/courses', [CourseController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('courses.authors', CourseAuthorController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->parameters(['authors' => 'author']);
    Route::get('courses/mine', [CourseController::class, 'mine']);
    Route::get('courses/mine/created', [CourseController::class, 'createMine']);
    Route::apiResource('courses', CourseController::class)->except(['index']);

    Route::post('/auth/logout', LogoutController::class);
    Route::get('/auth/me', fn(\Illuminate\Http\Request $r) => new \App\Http\Resources\UserResource($r->user()));
});
