<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Export mahasiswa data (Shared - Admin & Participant)
     */
    public function export(Request $request)
    {
        try {
            $query = Mahasiswa::with([
                'slta',
                'jalurDaftar',
                'kabupatenKota.provinsi',
                'importer',
                'approver'
            ]);

            // Filter by angkatan if provided
            if ($request->has('angkatan')) {
                $query->where('angkatan', $request->angkatan);
            }

            // Filter by kelas if provided
            if ($request->has('kelas')) {
                $query->where('kelas', $request->kelas);
            }

            // Filter by tahun if provided
            if ($request->has('tahun')) {
                $query->whereYear('created_at', $request->tahun);
            }

            $data = $query->get();

            // Transform data untuk export
            $exportData = $data->map(function ($mhs) {
                return [
                    'ID' => $mhs->id,
                    'Kelas' => $mhs->kelas,
                    'Angkatan' => $mhs->angkatan,
                    'Tanggal Lahir' => $mhs->tgl_lahir?->format('Y-m-d'),
                    'Jenis Kelamin' => $mhs->jenis_kelamin,
                    'Agama' => $mhs->agama,
                    'Kode Pos' => $mhs->kode_pos,
                    'SLTA' => $mhs->slta?->nama_slta_resmi,
                    'Jalur Daftar' => $mhs->jalurDaftar?->nama_jalur_daftar,
                    'Kabupaten/Kota' => $mhs->kabupatenKota?->nama_kabupaten_kota,
                    'Provinsi' => $mhs->kabupatenKota?->provinsi?->nama_provinsi,
                    'Importer' => $mhs->importer?->name,
                    'Approver' => $mhs->approver?->name,
                    'Created At' => $mhs->created_at->format('Y-m-d H:i:s'),
                ];
            });

            // Log activity
            $filterDesc = [];
            if ($request->has('angkatan')) {
                $filterDesc[] = "angkatan {$request->angkatan}";
            }
            if ($request->has('kelas')) {
                $filterDesc[] = "kelas {$request->kelas}";
            }

            $description = "User exported " . $data->count() . " records";
            if (!empty($filterDesc)) {
                $description .= " (" . implode(', ', $filterDesc) . ")";
            }

            $this->activityLogService->log(
                'export_data',
                $description,
                auth()->id(),
                $request
            );

            // Return JSON for now (nanti bisa diganti dengan Excel/CSV download)
            return response()->json([
                'message' => 'Data exported successfully',
                'total_records' => $data->count(),
                'data' => $exportData,
            ], 200);

            // TODO: Implementasi download file Excel/CSV menggunakan maatwebsite/excel
            // return Excel::download(new MahasiswaExport($exportData), 'mahasiswa.xlsx');

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Export failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
