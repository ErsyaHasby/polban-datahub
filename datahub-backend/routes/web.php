<?php

use Illuminate\Support\Facades\Route;

// Arahkan semua request ke view 'app' agar Vue Router yang menanganinya
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');