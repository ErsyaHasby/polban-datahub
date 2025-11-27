<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\AdminApprovalController;
use App\Http\Controllers\ExportController;

// Rute Publik
Route::post('/login', [AuthController::class, 'login']);

// Rute Terproteksi (Harus Login)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Download Data (ExportController mengambil dari data Mahasiswa yang sudah di-approve)
    Route::get('/export-data', [ExportController::class, 'export']);

    // PARTICIPANT
    Route::middleware('role:participant')->group(function () {
        Route::post('/import-data', [ImportController::class, 'store']);
        Route::get('/my-uploads', [ImportController::class, 'myUploads']);
    });

    // ADMIN
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        
        // Import History & Review
        Route::get('/pending-imports', [AdminApprovalController::class, 'getPending']);
        Route::get('/pending-imports/{batch_id}', [AdminApprovalController::class, 'getDetail']);
        
        // Action Approve/Reject
        Route::post('/approve/{batch_id}', [AdminApprovalController::class, 'approve']);
        Route::post('/reject/{batch_id}', [AdminApprovalController::class, 'reject']);
        
        // Action DELETE (FITUR BARU)
        // Rute ini harus menggunakan method DELETE dan path yang sesuai.
        Route::delete('/delete-batch/{batch_id}', [AdminApprovalController::class, 'deleteBatch']); 
        
        // Logs
        Route::get('/activity-logs', [AdminApprovalController::class, 'getLogs']);
    });
});