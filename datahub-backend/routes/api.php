<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Proteksi berdasarkan role
    Route::middleware('role:admin')->group(function() {
        Route::get('/admin/dashboard', function() {
            return response()->json(['message' => 'Welcome Admin!']);
        });
    });

    Route::middleware('role:participant')->group(function() {
        Route::get('/participant/dashboard', function() {
            return response()->json(['message' => 'Welcome Participant!']);
        });
    });

    Route::middleware('role:viewer')->group(function() {
        Route::get('/viewer/dashboard', function() {
            return response()->json(['message' => 'Welcome Viewer!']);
        });
    });
});
