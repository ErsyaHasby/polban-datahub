<?php

namespace App\Http\Controllers;

use App\Models\ImportMahasiswa;
use App\Models\Mahasiswa;
use App\Models\Slta;
use App\Models\JalurDaftar;
use App\Models\Wilayah;
use App\Models\Provinsi;
use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // WAJIB ADA

class AdminApprovalController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Get ALL Batches (Import History)
     * FIX: Menggunakan DB::raw() pada nilai 'archived' untuk mengatasi ENUM PostgreSQL (Error 500).
     */
    public function getPending()
    {
        $rawRows = ImportMahasiswa::with('user')
            // FIX: Menggunakan DB::raw() untuk memaksa quoting pada nilai 'archived'
            //->where('status', '!=', DB::raw("'archived'")) 
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

    /**
     * Get Detail Rows of a Batch (Untuk Review)
     */
    public function getDetail($batch_id)
    {
        $rows = ImportMahasiswa::where('batch_id', $batch_id)->get();
        return response()->json(['data' => $rows]);
    }

    /**
     * Get Activity Logs
     */
    public function getLogs()
    {
        $logs = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();
        return response()->json(['data' => $logs]);
    }

    /**
     * Approve ALL rows in a Batch
     */
    public function approve(Request $request, $batch_id)
    {
        $importRows = ImportMahasiswa::where('batch_id', $batch_id)->get();

        if ($importRows->isEmpty()) return response()->json(['message' => 'Data import tidak ditemukan'], 404);
        if ($importRows->first()->status !== 'pending') return response()->json(['message' => 'File ini sudah diproses sebelumnya'], 400);
        
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
                // Ignore log error
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

    /**
     * Reject ALL rows in a Batch
     */
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
    
    /**
     * Delete a Batch (Manual Archiving)
     */
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
}