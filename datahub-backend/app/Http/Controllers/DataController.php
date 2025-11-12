<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function dataMahasiswa()
    {
        $mahasiswa = Mahasiswa::with(['wilayah', 'slta', 'jalurDaftar'])->get();

        return response()->json([
            'total' => $mahasiswa->count(),
            'data' => $mahasiswa
        ], 200);
    }
}