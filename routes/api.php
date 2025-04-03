<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {

    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/register/update', [AuthController::class, 'updateRegister'])->middleware('auth:api');

    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

    Route::post('/delete', [AuthController::class, 'delete'])->middleware('auth:api');

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });
});


Route::get('/', function () {
    return response()->json(['message' => 'Pasquali Solution API', 'status' => 'Connected']);
});

Route::fallback(function () {
    return response()->json(['message' => 'Route not found', 'status' => 'Connected']);
});
