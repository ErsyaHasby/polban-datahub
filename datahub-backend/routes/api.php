<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controller Lama (Bawaan Ersyahasby)
use App\Http\Controllers\DataController; 

// Controller Baru (Dari Hanzz78)
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\AdminApprovalController;
use App\Http\Controllers\ExportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- 1. Rute Publik ---
Route::post('/login', [AuthController::class, 'login']);

// Rute bawaan DataHub (Data Mahasiswa) - Bisa dibuat publik atau dilindungi sesuai kebutuhan
Route::get('/data/mahasiswa', [DataController::class, 'dataMahasiswa']); 
// Note: Typo '/data/mahasisawa' di kode asli diperbaiki menjadi '/data/mahasiswa'


// --- 2. Rute Terproteksi (Harus Login) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth & User Profile
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Fitur Export (Bisa diakses user login)
    Route::get('/export-data', [ExportController::class, 'export']);

    // --- Rute Khusus Participant (Import Data) ---
    Route::middleware('role:participant')->group(function () {
        Route::post('/import-data', [ImportController::class, 'store']);
    });

    // --- Rute Khusus Admin (Approval & Logs) ---
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/pending-imports', [AdminApprovalController::class, 'getPending']);
        Route::get('/pending-imports/{import}', [AdminApprovalController::class, 'getDetail']);
        Route::post('/approve/{import}', [AdminApprovalController::class, 'approve']);
        Route::post('/reject/{import}', [AdminApprovalController::class, 'reject']);
        Route::get('/activity-logs', [AdminApprovalController::class, 'getLogs']);
    });
});