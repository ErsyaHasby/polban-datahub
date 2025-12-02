<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Services\ActivityLogService;
use App\Models\MahasiswaNilai;

class DataController extends Controller
{
    protected $activityLogService;
    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function dataMahasiswa(Request $request)
    {
        $mahasiswa = Mahasiswa::with(['slta', 'jalurDaftar','wilayah', 'provinsi' ])->get();
        $this->activityLogService->log(
            'get_mahasiswa_data',
            'datacore mengakses data mahasiswa',
            auth()->id(),
            $request
        );
        return response()->json([
            'total' => $mahasiswa->count(),
            'data' => $mahasiswa
        ], 200);
    }

    public function dataAkademik(Request $request)
    {
        $akademik = MahasiswaNilai::with([
            'nilaiMahasiswa' => function ($query) {
                $query->with(['mataKuliah', 'periode']);},// Nested Eager Loading untuk detail Nilai: Mata Kuliah dan Periode            
            'ip.periode'])->get();
        $this->activityLogService->log(
            'get_akademik_data',
            'datacore mengakses data akademik',
            auth()->id(),
            $request
        );
        return response()->json([
            'total' => $akademik->count(),
            'data' => $akademik
        ], 200);
    }
}