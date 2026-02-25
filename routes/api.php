<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PlanController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::apiResource('plans', PlanController::class);
    Route::apiResource('members', MemberController::class);
    Route::apiResource('payments', PaymentController::class);
});
