<?php

use App\Domain\TravelRequest\Controllers\TravelRequestController;
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

Route::prefix('travel')->group(function () {

    Route::get('/list', [TravelRequestController::class, 'index'])->middleware(['auth:api'])->name('travel.index');

    Route::post('/create', [TravelRequestController::class, 'store'])->middleware(['auth:api'])->name('travel.store');

    Route::put('/update', [TravelRequestController::class, 'updateTravel'])->middleware('auth:api')->name('travel.updateTravel');

    Route::put('/status/aprovar', [TravelRequestController::class, 'aprovar'])->middleware(['auth:api'])->name('travel.aprovar');

    Route::put('/status/cancelar', [TravelRequestController::class, 'cancelar'])->middleware(['auth:api'])->name('travel.cancelar');
});


Route::get('/', function () {
    return response()->json(['message' => 'Pasquali Solution API', 'status' => 'Connected']);
});

Route::fallback(function () {
    return response()->json(['message' => 'Route not found', 'status' => 'Connected']);
});
