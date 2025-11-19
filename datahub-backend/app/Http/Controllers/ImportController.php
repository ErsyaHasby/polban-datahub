<?php

namespace App\Http\Controllers;

use App\Models\ImportFile;      // Pastikan Model ini ada (lihat langkah migrasi di bawah)
use App\Models\ImportMahasiswa;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Store imported data (Participant only)
     * Menggunakan Logic: Upload File -> Save Record File -> Parse Rows
     */
    public function store(Request $request)
    {
        // 1. Validasi File
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,xlsx,xls,txt|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Gunakan DB Transaction agar jika error, data file & baris tidak tersimpan sebagian
        DB::beginTransaction();

        try {
            $user = $request->user(); // Ambil user dari request (lebih aman dari error IDE)
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            
            // 2. SIMPAN FILE FISIK KE STORAGE
            // File akan disimpan di folder: storage/app/public/imports
            // Pastikan folder storage sudah di-link: php artisan storage:link
            $filePath = $file->storeAs('imports', time() . '_' . $originalName, 'public');

            // 3. BUAT RECORD PARENT (IMPORT FILE)
            $importFile = ImportFile::create([
                'user_id' => $user->id, // Menggunakan variable $user agar tidak merah di VS Code
                'file_path' => $filePath,
                'original_name' => $originalName,
                'status' => 'pending',
            ]);

            // 4. PARSING DATA (Membaca isi file untuk dimasukkan ke tabel staging)
            $extension = $file->getClientOriginalExtension();
            $rowCount = 0;
            $errors = [];

            // --- LOGIKA UNTUK CSV/TXT ---
            if (in_array($extension, ['csv', 'txt'])) {
                $handle = fopen(storage_path('app/public/' . $filePath), 'r');
                
                // Skip header row (baris pertama)
                fgetcsv($handle);

                $lineNumber = 1;

                while (($row = fgetcsv($handle)) !== false) {
                    $lineNumber++;

                    if (empty(array_filter($row))) {
                        continue;
                    }

                    try {
                        ImportMahasiswa::create([
                            'import_file_id' => $importFile->id, // Relasi ke tabel import_files
                            'user_id' => $user->id,
                            'status' => 'pending',
                            'kelas' => isset($row[0]) && $row[0] !== '' ? $row[0] : null,
                            'angkatan' => isset($row[1]) && $row[1] !== '' ? (int)$row[1] : null,
                            'tgl_lahir' => isset($row[2]) && $row[2] !== '' ? $row[2] : null,
                            'jenis_kelamin' => isset($row[3]) && $row[3] !== '' ? $row[3] : null,
                            'agama' => isset($row[4]) && $row[4] !== '' ? $row[4] : null,
                            'kode_pos' => isset($row[5]) && $row[5] !== '' ? $row[5] : null,
                            'nama_slta_raw' => isset($row[6]) && $row[6] !== '' ? $row[6] : null,
                            'nama_jalur_daftar_raw' => isset($row[7]) && $row[7] !== '' ? $row[7] : null,
                            'nama_wilayah_raw' => isset($row[8]) && $row[8] !== '' ? $row[8] : null,
                            'provinsi_raw' => isset($row[9]) && $row[9] !== '' ? $row[9] : null,
                        ]);
                        $rowCount++;
                    } catch (\Exception $e) {
                        $errors[] = "Baris {$lineNumber}: " . $e->getMessage();
                    }
                }

                fclose($handle);
            
            // --- LOGIKA UNTUK EXCEL (XLS/XLSX) ---
            } elseif (in_array($extension, ['xlsx', 'xls'])) {
                $spreadsheet = IOFactory::load(storage_path('app/public/' . $filePath));
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();

                if (empty($rows)) {
                    throw new \Exception('File Excel kosong');
                }

                // Skip header row
                array_shift($rows);

                $lineNumber = 1;

                foreach ($rows as $row) {
                    $lineNumber++;

                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        continue;
                    }

                    try {
                        ImportMahasiswa::create([
                            'import_file_id' => $importFile->id, // Relasi ke tabel import_files
                            'user_id' => $user->id,
                            'status' => 'pending',
                            'kelas' => isset($row[0]) && $row[0] !== '' ? $row[0] : null,
                            'angkatan' => isset($row[1]) && $row[1] !== '' ? (int)$row[1] : null,
                            'tgl_lahir' => isset($row[2]) && $row[2] !== '' ? $row[2] : null,
                            'jenis_kelamin' => isset($row[3]) && $row[3] !== '' ? $row[3] : null,
                            'agama' => isset($row[4]) && $row[4] !== '' ? $row[4] : null,
                            'kode_pos' => isset($row[5]) && $row[5] !== '' ? $row[5] : null,
                            'nama_slta_raw' => isset($row[6]) && $row[6] !== '' ? $row[6] : null,
                            'nama_jalur_daftar_raw' => isset($row[7]) && $row[7] !== '' ? $row[7] : null,
                            'nama_wilayah_raw' => isset($row[8]) && $row[8] !== '' ? $row[8] : null,
                            'provinsi_raw' => isset($row[9]) && $row[9] !== '' ? $row[9] : null,
                        ]);
                        $rowCount++;
                    } catch (\Exception $e) {
                        $errors[] = "Baris {$lineNumber}: " . $e->getMessage();
                    }
                }
            } else {
                throw new \Exception('Format file tidak didukung.');
            }

            // Commit Database Transaction (Simpan permanen)
            DB::commit();

            $this->activityLogService->log(
                'import_data',
                "User uploaded file: {$originalName} ({$rowCount} rows)",
                $user->id,
                $request
            );

            $response = [
                'message' => 'File uploaded successfully. Waiting for approval.',
                'file_id' => $importFile->id,
                'rows_imported' => $rowCount,
            ];

            if (!empty($errors)) {
                $response['errors'] = array_slice($errors, 0, 10);
            }

            return response()->json($response, 201);

        } catch (\Exception $e) {
            // Rollback Database Transaction (Batalkan semua jika error)
            DB::rollBack();

            // Hapus file fisik jika upload gagal di tengah jalan
            if (isset($filePath) && file_exists(storage_path('app/public/' . $filePath))) {
                unlink(storage_path('app/public/' . $filePath));
            }

            return response()->json([
                'message' => 'Import failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}