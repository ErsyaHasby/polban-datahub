<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\dataController;

Route::get('/data/mahasisawa', [dataController::class, 'dataMahasiswa']);

// Route::get("/data/akademik", [...]);
// Route::get("/data/dosen", [...]);    