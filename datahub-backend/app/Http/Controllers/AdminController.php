<?php

namespace App\Http\Controllers;

use App\Models\ImportMahasiswa;
use App\Models\Mahasiswa;
use App\Models\Slta;
use App\Models\JalurDaftar;
use App\Models\Wilayah;
use App\Models\Provinsi;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function getPending()
    {
        $rawRows = ImportMahasiswa::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Grouping manual by batch_id di PHP
        $batches = $rawRows->groupBy('batch_id')->map(function ($rows) {
            $first = $rows->first();
            return [
                'batch_id' => $first->batch_id,
                'filename' => $first->filename,
                'created_at' => $first->created_at,
                'total_rows' => $rows->count(),
                // Menggunakan optional() untuk menghindari crash jika relasi User null
                'user_name' => optional($first->user)->name ?? 'Unknown User',
                'status' => $first->status,
                'admin_notes' => $first->admin_notes,
            ];
        })->values();
        return response()->json(['data' => $batches]);
    }

    /*Get Detail Rows of a Batch (Untuk Review)*/
    public function getDetail($batch_id)
    {
        $rows = ImportMahasiswa::where('batch_id', $batch_id)->get();
        return response()->json(['data' => $rows]);
    }

    /*Get Activity Logs*/
    public function getLogs()
    {
        $logs = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();
        return response()->json(['data' => $logs]);
    }

    public function approve(Request $request, $batch_id)
    {
        $importRows = ImportMahasiswa::where('batch_id', $batch_id)->get();
        if ($importRows->isEmpty())
            return response()->json(['message' => 'Data import tidak ditemukan'], 404);
        if ($importRows->first()->status !== 'pending')
            return response()->json(['message' => 'File ini sudah diproses sebelumnya'], 400);
        DB::beginTransaction();
        try {
            foreach ($importRows as $import) {
                // --- LOGIKA DATA MAPPING & MATCHING ---
                $raw_jk = trim(strtolower($import->jenis_kelamin));
                $mapped_jk = match ($raw_jk) {
                    'laki-laki', 'l' => 'L',
                    'perempuan', 'p' => 'P',
                    default => null,
                };

                $slta_id = null;
                if ($import->nama_slta_raw) {
                    $slta_id = Slta::where('nama_slta', $import->nama_slta_raw)->value('slta_id');
                }

                $jalur_id = null;
                if ($import->nama_jalur_daftar_raw) {
                    $jalur_id = JalurDaftar::where('nama_jalur_daftar', $import->nama_jalur_daftar_raw)->value('jalur_daftar_id');
                }

                $wilayah_id = null;
                if ($import->provinsi_raw && $import->nama_wilayah_raw) {
                    $provinsi_id = Provinsi::where('nama_provinsi', $import->provinsi_raw)->value('provinsi_id');

                    if ($provinsi_id) {
                        $wilayah_id = Wilayah::where('nama_wilayah', $import->nama_wilayah_raw)
                            ->where('provinsi_id', $provinsi_id)
                            ->value('wilayah_id');
                    }
                }

                // --- INSERT KE TABEL UTAMA (MAHASISWA) ---
                Mahasiswa::create([
                    'import_id' => $import->import_id,
                    'user_id_importer' => $import->user_id,
                    'user_id_approver' => auth()->id(),

                    'kelas' => $import->kelas,
                    'angkatan' => $import->angkatan,
                    'tgl_lahir' => $import->tgl_lahir,
                    'jenis_kelamin' => $mapped_jk,
                    'agama' => $import->agama,
                    'kode_pos' => $import->kode_pos,

                    'slta_id' => $slta_id,
                    'jalur_daftar_id' => $jalur_id,
                    'wilayah_id' => $wilayah_id,
                ]);
            }

            // Update Status Import menjadi Approved
            ImportMahasiswa::where('batch_id', $batch_id)->update(['status' => 'approved']);

            DB::commit();

            // LOGGING DIPINDAHKAN KELUAR DARI TRANSAKSI
            try {
                $this->activityLogService->log('approve_batch', "Approved batch {$batch_id}", auth()->id(), $request);
            } catch (\Exception $logE) {

            }
            return response()->json(['message' => 'File berhasil disetujui dan data masuk ke database utama.'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            // Kembalikan error spesifik dari server untuk debugging
            return response()->json([
                'message' => 'Gagal melakukan approval karena error server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /*Reject ALL rows in a Batch*/
    public function reject(Request $request, $batch_id)
    {
        $request->validate(['notes' => 'required|string']);

        $count = ImportMahasiswa::where('batch_id', $batch_id)->count();
        if ($count === 0) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        try {
            ImportMahasiswa::where('batch_id', $batch_id)
                ->update(['status' => 'rejected', 'admin_notes' => $request->notes]);

            $this->activityLogService->log('reject_batch', "Rejected batch {$batch_id}", auth()->id(), $request);

            return response()->json(['message' => 'File berhasil ditolak.'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'File berhasil ditolak (terjadi error pada pencatatan log).'], 200);
        }
    }

    /* Delete a Batch (Manual Archiving)*/
    public function deleteBatch(Request $request, $batch_id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        // Ubah status menjadi 'archived' (Soft Delete untuk Admin View)
        $updatedCount = ImportMahasiswa::where('batch_id', $batch_id)
            ->update(['status' => 'archived']);

        if ($updatedCount === 0) {
            return response()->json(['message' => 'Batch tidak ditemukan.'], 404);
        }

        $this->activityLogService->log('delete_batch', "Archived import batch {$batch_id} (Admin Delete)", auth()->id(), $request);

        return response()->json(['message' => 'Batch berhasil diarsipkan dan disembunyikan.'], 200);
    }

    //memfilter data sesuai status approved dan per bacth id
    public function getApprovedBatches(Request $request)
    {
        $search = $request->get('search', '');
        
        // Ambil batch_id unik yang sudah di-approve dengan informasi lengkap
        $query = ImportMahasiswa::with('user')
            ->select('batch_id')
            ->selectRaw("MAX(filename) as filename")
            ->selectRaw("MAX(created_at) as created_at")
            ->selectRaw("MAX(user_id) as user_id")
            ->selectRaw("COUNT(*) as total_rows")
            ->where('status', 'approved');

        // Tambahkan filter search jika ada
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('filename', 'ILIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'ILIKE', "%{$search}%");
                  });
            });
        }

        $approvedBatches = $query
            ->groupBy('batch_id')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($batch) {
                // Get user info for each batch
                $user = User::find($batch->user_id);
                return [
                    'batch_id' => $batch->batch_id,
                    'filename' => $batch->filename,
                    'created_at' => $batch->created_at,
                    'user_name' => $user ? $user->name : 'Unknown User',
                    'total_rows' => $batch->total_rows,
                    'status' => 'approved'
                ];
            });

        return response()->json(['data' => $approvedBatches]);
    }

    /*Export data Mahasiswa final berdasarkan batch_id.*/
    public function exportByBatch(Request $request, $batch_id)
    {
        try {
            // Cari data final MAHASISWA yang import_id-nya sesuai batch_id
            // Ambil import_id dari ImportMahasiswa dengan batch_id tertentu
            $importIds = ImportMahasiswa::where('batch_id', $batch_id)
                ->where('status', 'approved')
                ->pluck('import_id');

            if ($importIds->isEmpty()) {
                return response()->json(['message' => 'Tidak ada data import yang disetujui untuk Batch ID tersebut.'], 404);
            }

            // Ambil data Mahasiswa berdasarkan import_id
            $mahasiswa = Mahasiswa::whereIn('import_id', $importIds)
                ->with(['slta', 'jalurDaftar', 'wilayah.provinsi'])
                ->get();

            if ($mahasiswa->isEmpty()) {
                return response()->json(['message' => 'Tidak ada data Mahasiswa yang disetujui untuk Batch ID tersebut.'], 404);
            }

            // Persiapan File CSV
            $filename = 'data_mahasiswa_batch_' . $batch_id . '_' . now()->format('Ymd_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            // Logika Pembuatan File CSV (Menggunakan relasi untuk data yang lebih kaya)
            $callback = function () use ($mahasiswa) {
                $file = fopen('php://output', 'w');

                // Header Kolom (Ditambahkan detail relasi)
                fputcsv($file, [
                    'Import ID',
                    'Nama SLTA',
                    'Jalur Daftar',
                    'Wilayah',
                    'Provinsi',
                    'Kelas',
                    'Angkatan',
                    'Tgl Lahir',
                    'Jenis Kelamin',
                    'Agama',
                    'Kode Pos',
                    'Tanggal Approval'
                ]);

                // Isi Data
                foreach ($mahasiswa as $m) {
                    fputcsv($file, [
                        $m->import_id,
                        $m->slta->nama_slta ?? '-',
                        $m->jalurDaftar->nama_jalur_daftar ?? '-',
                        $m->wilayah->nama_wilayah ?? '-',
                        $m->wilayah->provinsi->nama_provinsi ?? '-',
                        $m->kelas,
                        $m->angkatan,
                        $m->tgl_lahir,
                        $m->jenis_kelamin,
                        $m->agama,
                        $m->kode_pos,
                        $m->approver_at ?? '-'
                    ]);
                }
                fclose($file);
            };

            // Catat Aktivitas - skip logging untuk sementara karena enum issue
            // $this->activityLogService->log(
            //     'export_data', 
            //     "Admin exported approved batch {$batch_id} to CSV.", 
            //     auth()->id(), 
            //     $request
            // );

            // Kirim file sebagai response
            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return response()->json(['message' => 'Error saat mengexport data: ' . $e->getMessage()], 500);
        }
    }

    // Debug method to check data availability
    public function debugBatch(Request $request, $batch_id)
    {
        $importIds = ImportMahasiswa::where('batch_id', $batch_id)
            ->where('status', 'approved')
            ->get();

        $mahasiswa = [];
        if (!$importIds->isEmpty()) {
            $mahasiswa = Mahasiswa::whereIn('import_id', $importIds->pluck('import_id'))
                ->with(['slta', 'jalurDaftar', 'wilayah.provinsi'])
                ->get();
        }

        return response()->json([
            'batch_id' => $batch_id,
            'import_data_count' => $importIds->count(),
            'mahasiswa_count' => count($mahasiswa),
            'import_data' => $importIds->take(3),
            'mahasiswa_sample' => collect($mahasiswa)->take(2),
        ]);
    }
}