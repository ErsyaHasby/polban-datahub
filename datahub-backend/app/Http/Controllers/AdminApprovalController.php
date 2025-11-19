<?php

namespace App\Http\Controllers;

use App\Models\ImportFile;
use App\Models\Mahasiswa;
use App\Models\Slta;
use App\Models\JalurDaftar;
use App\Models\KabupatenKota;
use App\Models\Provinsi;
use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminApprovalController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Get all pending FILE imports
     */
    public function getPending()
    {
        $files = ImportFile::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $files,
        ], 200);
    }

    /**
     * Get detail of specific FILE import
     */
    public function getDetail($id)
    {
        $file = ImportFile::with(['user', 'importRows'])->findOrFail($id);

        return response()->json([
            'data' => $file,
        ], 200);
    }

    /**
     * Approve ONE FILE (Logic V5 - Bulk Insert)
     */
    public function approve(Request $request, $id)
    {
        $importFile = ImportFile::with('importRows')->findOrFail($id);

        if ($importFile->status !== 'pending') {
            return response()->json(['message' => 'File already processed'], 400);
        }

        // Ambil user ID dari request untuk menghindari error intelephense
        $adminId = $request->user()->id;

        DB::beginTransaction();
        try {
            $rows = $importFile->importRows;
            $approvedCount = 0;

            foreach ($rows as $row) {
                // 1. Cari SLTA
                $slta_id = null;
                if ($row->nama_slta_raw) {
                    $slta_id = Slta::where('nama_slta_resmi', $row->nama_slta_raw)->value('id');
                }

                // 2. Cari Jalur Daftar
                $jalur_id = null;
                if ($row->nama_jalur_daftar_raw) {
                    $jalur_id = JalurDaftar::where('nama_jalur_daftar', $row->nama_jalur_daftar_raw)->value('id');
                }

                // 3. Logika Wilayah
                $kabupaten_id = null;
                if ($row->provinsi_raw) {
                    $provinsi_id = Provinsi::where('nama_provinsi', $row->provinsi_raw)->value('id');
                    if ($provinsi_id && $row->nama_wilayah_raw) {
                        $kabupaten_id = KabupatenKota::where('nama_kabupaten_kota', $row->nama_wilayah_raw)
                            ->where('id_provinsi', $provinsi_id)
                            ->value('id');
                    }
                }

                // 4. Insert ke Tabel Utama Mahasiswa
                Mahasiswa::create([
                    'import_id' => $row->id,
                    'user_id_importer' => $importFile->user_id,
                    'user_id_approver' => $adminId, // PERBAIKAN DI SINI (Line 100-an)
                    'kelas' => $row->kelas,
                    'angkatan' => $row->angkatan,
                    'tgl_lahir' => $row->tgl_lahir,
                    'jenis_kelamin' => $row->jenis_kelamin,
                    'agama' => $row->agama,
                    'kode_pos' => $row->kode_pos,
                    'id_slta' => $slta_id,
                    'id_jalur_daftar' => $jalur_id,
                    'id_kabupaten_kota' => $kabupaten_id,
                ]);

                $row->status = 'approved';
                $row->save();
                $approvedCount++;
            }

            $importFile->status = 'approved';
            $importFile->save();

            DB::commit();

            $this->activityLogService->log(
                'approve_file',
                "Admin approved file #{$importFile->id} with {$approvedCount} rows",
                $adminId, // PERBAIKAN DI SINI (Line 127-an)
                $request
            );

            return response()->json([
                'message' => 'File approved successfully',
                'rows_processed' => $approvedCount
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Approval failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reject FILE
     */
    public function reject(Request $request, $id)
    {
        $importFile = ImportFile::findOrFail($id);

        if ($importFile->status !== 'pending') {
            return response()->json(['message' => 'File already processed'], 400);
        }

        $request->validate(['notes' => 'required|string|max:500']);

        // Ambil user ID dari request
        $adminId = $request->user()->id;

        try {
            $importFile->status = 'rejected';
            $importFile->admin_feedback = $request->input('notes');
            $importFile->save();

            $importFile->importRows()->update(['status' => 'rejected']);

            $this->activityLogService->log(
                'reject_file',
                "Admin rejected file #{$importFile->id}",
                $adminId, // PERBAIKAN DI SINI (Line 170-an)
                $request
            );

            return response()->json(['message' => 'File rejected successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Rejection failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function getLogs(Request $request)
    {
        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');

        if ($request->has('action')) {
            $query->where('action', $request->action);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->paginate($request->input('per_page', 50));

        return response()->json($logs, 200);
    }
}