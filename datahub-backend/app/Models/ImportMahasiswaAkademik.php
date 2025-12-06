<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportMahasiswaAkademik extends Model
{
    protected $table = 'import_mahasiswa_akademik';
    protected $primaryKey = 'import_akademik_id';

    protected $fillable = [
        'user_id',
        'batch_id',
        'filename',
        'status',
        'mahasiswa_id',
        'angkatan',
        'tahun_ajaran',
        'semester',
        'kode_mk',
        'nama_mk',
        'sks',
        'nilai_huruf',
        'ip_semester',
        'ipk',
        'admin_notes',
        'error_message',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'mahasiswa_id' => 'integer',
        'angkatan' => 'integer',
        'tahun_ajaran' => 'integer',
        'sks' => 'integer',
        'ip_semester' => 'decimal:2',
        'ipk' => 'decimal:2',
    ];

    /**
     * Relasi ke User yang melakukan import
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relasi ke Mahasiswa Akademik
     */
    public function mahasiswaAkademik(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id', 'mahasiswa_id');
    }

    /**
     * Scope untuk filter berdasarkan batch
     */
    public function scopeByBatch($query, $batchId)
    {
        return $query->where('batch_id', $batchId);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan angkatan
     */
    public function scopeByAngkatan($query, $angkatan)
    {
        return $query->where('angkatan', $angkatan);
    }

    /**
     * Check if import has errors
     */
    public function hasErrors(): bool
    {
        return !empty($this->error_message);
    }

    /**
     * Get all imports by batch with grouping
     */
    public static function getImportSummary($batchId)
    {
        return self::where('batch_id', $batchId)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();
    }
}
