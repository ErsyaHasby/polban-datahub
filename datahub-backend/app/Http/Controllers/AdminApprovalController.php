<?php

namespace App\Http\Controllers;

use App\Models\ImportMahasiswa;
use App\Models\Mahasiswa;
use App\Models\Slta;
use App\Models\JalurDaftar;
use App\Models\KabupatenKota;
use App\Models\Provinsi;
use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminApprovalController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function getPending()
    {
        $imports = ImportMahasiswa::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $imports,
        ], 200);
    }

    public function getDetail(ImportMahasiswa $import)
    {
        $import->load('user');

        return response()->json([
            'data' => $import,
        ], 200);
    }

    public function approve(Request $request, ImportMahasiswa $import)
    {
        if ($import->status !== 'pending') {
            return response()->json([
                'message' => 'Import already processed',
            ], 400);
        }

        try {
            $slta_id = null;
            if ($import->nama_slta_raw) {
                $slta_id = Slta::where('nama_slta_resmi', $import->nama_slta_raw)->value('id');
            }

            $jalur_id = null;
            if ($import->nama_jalur_daftar_raw) {
                $jalur_id = JalurDaftar::where('nama_jalur_daftar', $import->nama_jalur_daftar_raw)->value('id');
            }

            $kabupaten_id = null;
            $provinsi_id = null;

            if ($import->provinsi_raw) {
                $provinsi_id = Provinsi::where('nama_provinsi', $import->provinsi_raw)->value('id');

                if ($provinsi_id && $import->nama_wilayah_raw) {
                    $kabupaten_id = KabupatenKota::where('nama_kabupaten_kota', $import->nama_wilayah_raw)
                        ->where('id_provinsi', $provinsi_id)
                        ->value('id');
                }
            }

            $mahasiswa = Mahasiswa::create([
                'import_id' => $import->id,
                'user_id_importer' => $import->user_id,
                'user_id_approver' => Auth::id(),
                'kelas' => $import->kelas,
                'angkatan' => $import->angkatan,
                'tgl_lahir' => $import->tgl_lahir,
                'jenis_kelamin' => $import->jenis_kelamin,
                'agama' => $import->agama,
                'kode_pos' => $import->kode_pos,
                'id_slta' => $slta_id,
                'id_jalur_daftar' => $jalur_id,
                'id_kabupaten_kota' => $kabupaten_id,
            ]);

            $import->status = 'approved';
            $import->save();

            $this->activityLogService->log(
                'approve_data',
                "Admin approved import data #" . $import->id,
                Auth::id(),
                $request
            );

            return response()->json([
                'message' => 'Data approved successfully',
                'mahasiswa' => $mahasiswa,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Approval failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function reject(Request $request, ImportMahasiswa $import)
    {
        if ($import->status !== 'pending') {
            return response()->json([
                'message' => 'Import already processed',
            ], 400);
        }

        $request->validate([
            'notes' => 'required|string|max:500',
        ]);

        try {
            $import->status = 'rejected';
            $import->admin_notes = $request->input('notes');
            $import->save();

            $this->activityLogService->log(
                'reject_data',
                "Admin rejected import data #" . $import->id,
                Auth::id(),
                $request
            );

            return response()->json([
                'message' => 'Data rejected successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Rejection failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getLogs(Request $request)
    {
        $query = ActivityLog::with('user')
            ->orderBy('created_at', 'desc');

        if ($request->has('action')) {
            $query->where('action', $request->action);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $perPage = $request->input('per_page', 50);
        $logs = $query->paginate($perPage);

        return response()->json($logs, 200);
    }
}