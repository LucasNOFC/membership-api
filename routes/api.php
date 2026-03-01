<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\UserController;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::prefix('auth')->group(function () {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/logout', [UserController::class, 'logout']);
});



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'getUsers']);
    Route::post('/auth/logout', [UserController::class, 'logout']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/me', function (Request $request) {
        return new UserResource($request->user());
    });
    Route::apiResource('members', MemberController::class);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/users/delete', [UserController::class], 'delete');
    Route::apiResource('plans', PlanController::class);
    Route::apiResource('payments', PaymentController::class);
});
