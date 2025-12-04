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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /* Mendapatkan daftar import yang statusnya Pending */
    public function getPending()
    {
        $rawRows = ImportMahasiswa::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Grouping berdasarkan batch_id
        $batches = $rawRows->groupBy('batch_id')->map(function ($rows) {
            $first = $rows->first();
            return [
                'batch_id' => $first->batch_id,
                'filename' => $first->filename,
                'created_at' => $first->created_at,
                'total_rows' => $rows->count(),
                'user_name' => optional($first->user)->name ?? 'Unknown User',
                'status' => $first->status,
                'admin_notes' => $first->admin_notes,
            ];
        })->values();
        
        return response()->json(['data' => $batches]);
    }

    /* Mendapatkan detail baris data dari sebuah Batch */
    public function getDetail($batch_id)
    {
        $rows = ImportMahasiswa::where('batch_id', $batch_id)->get();
        return response()->json(['data' => $rows]);
    }

    /* Mendapatkan Log Aktivitas Admin/User */
    public function getLogs()
    {
        $logs = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();
        return response()->json(['data' => $logs]);
    }

    /* * FITUR UTAMA: APPROVE DENGAN VALIDASI REFERENSI (RULE 6) 
     * Mengecek ID SLTA, Jalur, dan Wilayah sebelum insert ke tabel utama.
     */
    public function approve(Request $request, $batch_id)
    {
        $importRows = ImportMahasiswa::where('batch_id', $batch_id)->get();

        if ($importRows->isEmpty())
            return response()->json(['message' => 'Data import tidak ditemukan'], 404);
        
        if ($importRows->first()->status !== 'pending')
            return response()->json(['message' => 'File ini sudah diproses sebelumnya'], 400);

        DB::beginTransaction();
        
        $successCount = 0;
        $failedCount = 0;

        try {
            foreach ($importRows as $import) {
                // 1. Mapping Gender (Normalisasi L/P)
                $raw_jk = trim(strtolower($import->jenis_kelamin ?? ''));
                $mapped_jk = match ($raw_jk) {
                    'laki-laki', 'l' => 'L',
                    'perempuan', 'p' => 'P',
                    default => null,
                };

                // 2. Lookup Data Referensi (Case Insensitive Search)
                
                // Cek SLTA
                $slta = null;
                if ($import->nama_slta_raw) {
                    $slta = Slta::whereRaw('LOWER(nama_slta) = ?', [strtolower(trim($import->nama_slta_raw))])->first();
                }

                // Cek Jalur Pendaftaran
                $jalur = null;
                if ($import->nama_jalur_daftar_raw) {
                    $jalur = JalurDaftar::whereRaw('LOWER(nama_jalur_daftar) = ?', [strtolower(trim($import->nama_jalur_daftar_raw))])->first();
                }

                // Cek Wilayah & Provinsi
                $wilayah = null;
                if ($import->nama_wilayah_raw) {
                    // Jika ada provinsi, cek kombinasi wilayah + provinsi agar lebih akurat
                    if ($import->provinsi_raw) {
                        $provinsi = Provinsi::whereRaw('LOWER(nama_provinsi) = ?', [strtolower(trim($import->provinsi_raw))])->first();
                        if ($provinsi) {
                            $wilayah = Wilayah::where('provinsi_id', $provinsi->provinsi_id)
                                ->whereRaw('LOWER(nama_wilayah) = ?', [strtolower(trim($import->nama_wilayah_raw))])
                                ->first();
                        }
                    } else {
                        // Jika tidak ada provinsi, cari wilayah berdasarkan nama saja (ambil yang pertama ketemu)
                        $wilayah = Wilayah::whereRaw('LOWER(nama_wilayah) = ?', [strtolower(trim($import->nama_wilayah_raw))])->first();
                    }
                }

                // 3. Validasi Kelengkapan Data (Rule 6)
                // Jika ID Referensi UTAMA (SLTA & Jalur & Wilayah) ditemukan, masukkan ke tabel Mahasiswa.
                // Jika salah satu tidak ketemu, tandai FAILED.
                
                $errors = [];
                if (!$slta) $errors[] = "SLTA '{$import->nama_slta_raw}' tidak ditemukan";
                if (!$jalur) $errors[] = "Jalur '{$import->nama_jalur_daftar_raw}' tidak ditemukan";
                if (!$wilayah) $errors[] = "Wilayah '{$import->nama_wilayah_raw}' tidak ditemukan";
                if (!$mapped_jk) $errors[] = "Format Gender '{$import->jenis_kelamin}' salah";

                if (empty($errors)) {
                    // --- SUCCESS: INSERT KE TABEL UTAMA ---
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

                        'slta_id' => $slta->slta_id,
                        'jalur_daftar_id' => $jalur->jalur_daftar_id,
                        'wilayah_id' => $wilayah->wilayah_id,
                        'status' => 'aktif'
                    ]);

                    // Update row import jadi approved
                    $import->update(['status' => 'approved', 'admin_notes' => 'OK']);
                    $successCount++;

                } else {
                    // --- FAIL: TANDAI ERROR, JANGAN MASUKKAN DATA ---
                    $import->update([
                        'status' => 'rejected',
                        'admin_notes' => implode('; ', $errors)
                    ]);
                    $failedCount++;
                }
            }

            DB::commit();

            // Log Summary
            try {
                $this->activityLogService->log(
                    'approve_batch', 
                    "Processed Batch {$batch_id}. Success: {$successCount}, Failed: {$failedCount}", 
                    auth()->id(), 
                    $request
                );
            } catch (\Exception $logE) {}

            return response()->json([
                'message' => 'Proses persetujuan selesai.',
                'summary' => [
                    'total' => $importRows->count(),
                    'approved' => $successCount,
                    'failed' => $failedCount
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal melakukan approval karena error server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /* Menolak seluruh batch (Reject) */
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

    /* Mengarsipkan Batch (Soft Delete) */
    public function deleteBatch(Request $request, $batch_id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $updatedCount = ImportMahasiswa::where('batch_id', $batch_id)
            ->update(['status' => 'archived']);

        if ($updatedCount === 0) {
            return response()->json(['message' => 'Batch tidak ditemukan.'], 404);
        }

        $this->activityLogService->log('delete_batch', "Archived import batch {$batch_id}", auth()->id(), $request);

        return response()->json(['message' => 'Batch berhasil diarsipkan.'], 200);
    }

    /* Mendapatkan Batch yang sudah Approved (untuk menu Download) */
    public function getApprovedBatches(Request $request)
    {
        $search = $request->get('search', '');
        
        $query = ImportMahasiswa::with('user')
            ->select('batch_id')
            ->selectRaw("MAX(filename) as filename")
            ->selectRaw("MAX(created_at) as created_at")
            ->selectRaw("MAX(user_id) as user_id")
            ->selectRaw("COUNT(*) as total_rows")
            ->where('status', 'approved');

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

    /* Export data final ke CSV berdasarkan Batch ID */
    public function exportByBatch(Request $request, $batch_id)
    {
        try {
            $importIds = ImportMahasiswa::where('batch_id', $batch_id)
                ->where('status', 'approved')
                ->pluck('import_id');

            if ($importIds->isEmpty()) {
                return response()->json(['message' => 'Tidak ada data import yang disetujui untuk Batch ID tersebut.'], 404);
            }

            $mahasiswa = Mahasiswa::whereIn('import_id', $importIds)
                ->with(['slta', 'jalurDaftar', 'wilayah.provinsi'])
                ->get();

            if ($mahasiswa->isEmpty()) {
                return response()->json(['message' => 'Tidak ada data Mahasiswa yang disetujui untuk Batch ID tersebut.'], 404);
            }

            $filename = 'data_mahasiswa_batch_' . $batch_id . '_' . now()->format('Ymd_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function () use ($mahasiswa) {
                $file = fopen('php://output', 'w');
                // Header CSV
                fputcsv($file, [
                    'Import ID', 'Nama SLTA', 'Jalur Daftar', 'Wilayah', 'Provinsi', 
                    'Kelas', 'Angkatan', 'Tgl Lahir', 'Jenis Kelamin', 'Agama', 'Kode Pos'
                ]);

                foreach ($mahasiswa as $m) {
                    fputcsv($file, [
                        $m->import_id,
                        $m->slta->nama_slta ?? '-',
                        $m->jalurDaftar->nama_jalur_daftar ?? '-',
                        $m->wilayah->nama_wilayah ?? '-',
                        $m->wilayah->provinsi->nama_provinsi ?? '-',
                        $m->kelas, $m->angkatan, $m->tgl_lahir, $m->jenis_kelamin,
                        $m->agama, $m->kode_pos
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return response()->json(['message' => 'Error saat mengexport data.'], 500);
        }
    }

    /* Debug method: Untuk mengecek apakah data masuk ke tabel mahasiswa */
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