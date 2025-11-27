<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    /**
     * Export data Mahasiswa yang sudah di-approve ke CSV.
     */
    public function export(Request $request)
    {
        // Ambil data dari tabel final Mahasiswa (Hanya data yang sudah di-Approve)
        $mahasiswa = Mahasiswa::orderBy('angkatan', 'desc')->get();

        if ($mahasiswa->isEmpty()) {
            return response()->json(['message' => 'Tidak ada data Mahasiswa yang disetujui untuk diexport.'], 404);
        }

        $filename = 'data_mahasiswa_polban_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($mahasiswa) {
            $file = fopen('php://output', 'w');

            // Header Kolom
            fputcsv($file, [
                'ID Import', 'Kelas', 'Angkatan', 'Tgl Lahir', 'Jenis Kelamin', 'Agama', 
                'Kode Pos', 'SLTA ID', 'Jalur Daftar ID', 'Wilayah ID'
            ]);

            // Isi Data
            foreach ($mahasiswa as $m) {
                fputcsv($file, [
                    $m->import_id,
                    $m->kelas,
                    $m->angkatan,
                    $m->tgl_lahir,
                    $m->jenis_kelamin,
                    $m->agama,
                    $m->kode_pos,
                    $m->slta_id,
                    $m->jalur_daftar_id,
                    $m->wilayah_id,
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}