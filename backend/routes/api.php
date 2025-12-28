<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\ServiceController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);

use App\Http\Controllers\BookingController;

// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/services', [ServiceController::class, 'store']);
    Route::post('/bookings', [BookingController::class, 'store']); // Create booking
    Route::get('/bookings', [BookingController::class, 'index']); // List my bookings
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
