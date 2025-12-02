<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ExportController;

Route::prefix('admin')
    // ->middleware(['auth:sanctum']) // sesuaikan guard Anda
    ->group(function () {
        Route::get('/files', [ExportController::class, 'index']);              // list riwayat import + search
        Route::get('/files/{batch}/download', [ExportController::class, 'download']); // unduh per-batch (CSV/XLSX)
        Route::get('/export-data', [ExportController::class, 'exportAll']);     // opsional: ekspor massal
    });