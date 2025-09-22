<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthTokenController;

Route::get('/health', fn() => ['status' => 'ok']);



Route::post('auth/token', [AuthTokenController::class, 'store'])->middleware('throttle:auth');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', fn(Request $r) => ['user' => $r->user()]);
    Route::delete('auth/token', [AuthTokenController::class, 'destroy']);
});


