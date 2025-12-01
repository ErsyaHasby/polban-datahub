<?php

namespace App\Http\Controllers;

use App\Models\ImportMahasiswa;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;   
use Illuminate\Support\Str;         
use PhpOffice\PhpSpreadsheet\IOFactory;

class ParticipantController extends Controller
{
    protected $activityLogService;
    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }
    /*Get User's Upload History (Grouped by Batch/File)*/
    public function myUploads(Request $request)
    {
        $uploads = DB::table('import_mahasiswa')
            ->select('batch_id')
            ->selectRaw("MAX(filename) as filename")
            ->selectRaw("MAX(status) as status")
            ->selectRaw("MAX(created_at) as created_at")
            ->selectRaw("MAX(admin_notes) as admin_notes")
            ->selectRaw("COUNT(*) as total_rows")
            ->where('user_id', auth()->id())
            //->where('status', '!=', 'archived')
            ->groupBy('batch_id')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['data' => $uploads]);
    }

    /*Store imported data Perbaikan: Skip kolom index 0 (No) dan perbaikan parameter Log*/
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,xlsx,xls,txt|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $file = $request->file('file');
            $originalFilename = $file->getClientOriginalName();
            $batchId = (string) Str::uuid();
            $now = now(); 

            $rowsToInsert = [];
            
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Skip Header (Baris 1)
            array_shift($rows);

            foreach ($rows as $row) {
                if (empty(array_filter($row))) continue;

                // Index digeser +1 karena ada kolom "No" di index 0
                $rowsToInsert[] = [
                    'user_id' => auth()->id(),
                    'batch_id' => $batchId,
                    'filename' => $originalFilename,
                    'status' => 'pending',
                    
                    // MAPPING DATA (Excel Column Index 1 = Kolom B)
                    'kelas'                 => $row[1] ?? null,
                    'angkatan'              => isset($row[2]) && $row[2] !== '' ? (int)$row[2] : null,
                    'tgl_lahir'             => $row[3] ?? null,
                    'jenis_kelamin'         => $row[4] ?? null,
                    'agama'                 => $row[5] ?? null,
                    'kode_pos'              => $row[6] ?? null,
                    'nama_slta_raw'         => $row[7] ?? null,
                    'nama_jalur_daftar_raw' => $row[8] ?? null,
                    'nama_wilayah_raw'      => $row[9] ?? null,
                    'provinsi_raw'          => $row[10] ?? null,
                    
                    'admin_notes' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if (count($rowsToInsert) > 0) {
                ImportMahasiswa::insert($rowsToInsert);
                
                // Log Activity dengan parameter lengkap
                $this->activityLogService->log(
                    'import_data',
                    "User uploaded file {$originalFilename}",
                    auth()->id(),
                    $request
                );

                return response()->json([
                    'message' => 'File uploaded successfully',
                    'batch_id' => $batchId,
                    'rows_imported' => count($rowsToInsert)
                ], 201);
            } else {
                throw new \Exception('File kosong atau format tidak terbaca');
            }

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Import failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}