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

    /* Upload History */
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
            ->groupBy('batch_id')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['data' => $uploads]);
    }

    /* Proses Upload & Validasi Header */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240',
            'jenis_data' => 'required|in:mahasiswa,akademik',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $file = $request->file('file');
            $jenisData = $request->input('jenis_data');
            
            // Baca File Spreadsheet
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            if (count($rows) < 2) {
                throw new \Exception("File kosong atau hanya berisi header.");
            }

            // Ambil Header (Baris 1) & Normalisasi (Lowercase)
            $fileHeaders = array_map(function($h) {
                return trim(strtolower($h));
            }, $rows[0]);
            
            array_shift($rows); // Hapus baris header dari data

            // ==========================================================
            // DEFINISI ATURAN KOLOM (Rules 1-5)
            // ==========================================================
            $rules = [
                'mahasiswa' => [
                    // Header yang WAJIB ada (penulisan harus sama/mirip)
                    'required' => [
                        'kelas', 'angkatan', 'tanggal lahir', 'jenis kelamin', 
                        'asal slta', 'jalur daftar', 'wilayah'
                    ],
                    // Header yang DILARANG (indikasi salah jenis file)
                    'forbidden' => ['nilai', 'sks', 'ipk'] 
                ],
                'akademik' => [
                    'required' => ['nim', 'kode mata kuliah', 'nilai', 'semester'],
                    'forbidden' => ['asal slta', 'jalur daftar']
                ]
            ];

            $currentRule = $rules[$jenisData];

            // Cek Kolom Wajib
            $missingColumns = array_diff($currentRule['required'], $fileHeaders);
            if (!empty($missingColumns)) {
                throw new \Exception("REJECT: Format salah! Kolom wajib berikut hilang: " . implode(', ', $missingColumns));
            }

            // Cek Salah File
            $foundForbidden = array_intersect($currentRule['forbidden'], $fileHeaders);
            if (!empty($foundForbidden)) {
                throw new \Exception("REJECT: Anda memilih jenis '$jenisData', tapi file mengandung kolom: " . implode(', ', $foundForbidden));
            }

            // ==========================================================
            // MAPPING DATA (Rule 5: Ignore kolom berlebih)
            // ==========================================================
            $headerMap = array_flip($fileHeaders); // Nama Kolom => Index Angka
            $batchId = (string) Str::uuid();
            $now = now();
            $rowsToInsert = [];

            foreach ($rows as $row) {
                // Skip baris kosong
                if (empty(array_filter($row, function($v) { return $v !== null && $v !== ''; }))) continue;

                // Helper ambil data
                $getVal = function($colName) use ($row, $headerMap) {
                    $idx = $headerMap[$colName] ?? null;
                    return $idx !== null ? ($row[$idx] ?? null) : null;
                };

                if ($jenisData == 'mahasiswa') {
                    $rowsToInsert[] = [
                        'user_id' => auth()->id(),
                        'batch_id' => $batchId,
                        'filename' => $file->getClientOriginalName(),
                        'status' => 'pending',
                        
                        // Mapping Data Mahasiswa
                        'kelas'                 => $getVal('kelas'),
                        'angkatan'              => (int) $getVal('angkatan'),
                        'tgl_lahir'             => $getVal('tanggal lahir'), 
                        'jenis_kelamin'         => $getVal('jenis kelamin'),
                        'agama'                 => $getVal('agama'),      // Opsional
                        'kode_pos'              => $getVal('kode pos'),   // Opsional
                        
                        // Simpan ke kolom RAW untuk divalidasi Admin
                        'nama_slta_raw'         => $getVal('asal slta'),
                        'nama_jalur_daftar_raw' => $getVal('jalur daftar'),
                        'nama_wilayah_raw'      => $getVal('wilayah'),
                        'provinsi_raw'          => $getVal('provinsi'), // Opsional
                        
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                // (Tambahkan blok 'else if ($jenisData == 'akademik')' jika sudah siap tabelnya)
            }

            // Insert Batch
            if (count($rowsToInsert) > 0) {
                foreach (array_chunk($rowsToInsert, 500) as $chunk) {
                    ImportMahasiswa::insert($chunk);
                }
                
                $this->activityLogService->log(
                    'import_data',
                    "User uploaded file {$file->getClientOriginalName()} as $jenisData",
                    auth()->id(),
                    $request
                );

                return response()->json([
                    'message' => 'Validasi berhasil. Data masuk antrian persetujuan admin.',
                    'batch_id' => $batchId,
                    'rows' => count($rowsToInsert)
                ], 201);
            } else {
                throw new \Exception("File terbaca tapi tidak ada data yang valid.");
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'Upload Gagal', 'error' => $e->getMessage()], 400);
        }
    }
}