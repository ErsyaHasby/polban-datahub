<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DataController;

// Rute Publik
Route::post('/login', [AuthController::class, 'login']);

// Rute Terproteksi (Harus Login)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user(); });
    // DATACORE
    Route::middleware('role:datacore')->group(function () {
        Route::get('/datacore/mahasiswa', [DataController::class, 'dataMahasiswa']);
        Route::get('/datacore/akademik', [DataController::class, 'dataAkademik']);
    });
    // PARTICIPANT
    Route::middleware('role:participant')->group(function () {
        //Route::post('/import-data', [ParticipantController::class, 'store']);
        Route::post('/participant/upload', [ParticipantController::class, 'store']);
        Route::get('/my-uploads', [ParticipantController::class, 'myUploads']);
    });
    // ADMIN
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // Import History & Review
        Route::get('/pending-imports', [AdminController::class, 'getPending']);
        Route::get('/pending-imports/{batch_id}', [AdminController::class, 'getDetail']);
        // Action Approve/Reject
        Route::post('/approve/{batch_id}', [AdminController::class, 'approve']);
        Route::post('/reject/{batch_id}', [AdminController::class, 'reject']);
        // Action DELETE 
        Route::delete('/delete-batch/{batch_id}', [AdminController::class, 'deleteBatch']);
        // Logs
        Route::get('/activity-logs', [AdminController::class, 'getLogs']);
        // 1. Ambil daftar batch_id yang sudah di-approve
        Route::get('/approved-batches', [AdminController::class, 'getApprovedBatches']);
        // 2. Download data berdasarkan batch_id
        Route::get('/export-by-batch/{batch_id}', [AdminController::class, 'exportByBatch']);
        Route::get('/files/{batch_id}/download', [AdminController::class, 'exportByBatch']);
        // Debug endpoint to check data
        Route::get('/debug-batch/{batch_id}', [AdminController::class, 'debugBatch']);
    });
});