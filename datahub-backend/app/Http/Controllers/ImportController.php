<?php

namespace App\Http\Controllers;

use App\Models\ImportMahasiswa;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,xlsx,xls,txt|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            
            if (in_array($extension, ['csv', 'txt'])) {
                list($rowCount, $errors) = $this->processCsv($file->getRealPath());
            } elseif (in_array($extension, ['xlsx', 'xls'])) {
                list($rowCount, $errors) = $this->processExcel($file->getRealPath());
            } else {
                throw new \InvalidArgumentException('Format file tidak didukung.');
            }

            $this->activityLogService->log(
                'import_data',
                "User imported {$rowCount} data rows",
                Auth::id(),
                $request
            );

            $response = [
                'message' => 'Data imported successfully',
                'rows_imported' => $rowCount,
            ];

            if (!empty($errors)) {
                $response['errors'] = array_slice($errors, 0, 10);
            }

            return response()->json($response, 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Import failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function processCsv($filePath)
    {
        $handle = fopen($filePath, 'r');
        $header = fgetcsv($handle);
        
        if (!$header) {
            fclose($handle);
            throw new \InvalidArgumentException('File CSV kosong');
        }

        $rowCount = 0;
        $errors = [];
        $lineNumber = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $lineNumber++;
            if (empty(array_filter($row))) continue;

            try {
                $this->createImportRecord($row);
                $rowCount++;
            } catch (\Exception $e) {
                $errors[] = "Baris {$lineNumber}: " . $e->getMessage();
            }
        }

        fclose($handle);
        return [$rowCount, $errors];
    }

    private function processExcel($filePath)
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        if (empty($rows)) {
            throw new \InvalidArgumentException('File Excel kosong');
        }

        // Skip header
        array_shift($rows);

        $rowCount = 0;
        $errors = [];
        $lineNumber = 1;

        foreach ($rows as $row) {
            $lineNumber++;
            if (empty(array_filter($row))) continue;

            try {
                $this->createImportRecord($row);
                $rowCount++;
            } catch (\Exception $e) {
                $errors[] = "Baris {$lineNumber}: " . $e->getMessage();
            }
        }

        return [$rowCount, $errors];
    }

    private function createImportRecord($row)
    {
        ImportMahasiswa::create([
            'user_id' => Auth::id(),
            'status' => 'pending',
            'kelas' => $row[0] ?? null,
            'angkatan' => isset($row[1]) && $row[1] !== '' ? (int)$row[1] : null,
            'tgl_lahir' => $row[2] ?? null,
            'jenis_kelamin' => $row[3] ?? null,
            'agama' => $row[4] ?? null,
            'kode_pos' => $row[5] ?? null,
            'nama_slta_raw' => $row[6] ?? null,
            'nama_jalur_daftar_raw' => $row[7] ?? null,
            'nama_wilayah_raw' => $row[8] ?? null,
            'provinsi_raw' => $row[9] ?? null,
        ]);
    }
}