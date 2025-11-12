<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';
    protected $primaryKey = 'mahasiswa_id';

    protected $fillable = [
        'import_id',
        'user_id_importer',
        'user_id_approver',
        'kelas',
        'angkatan',
        'tgl_lahir',
        'jenis_kelamin',
        'agama',
        'kode_pos',
        'slta_id',
        'jalurdaftar_id',
        'wilayah_id',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
    ];

    /**
     * Get the import record.
     */
    public function import()
    {
        return $this->belongsTo(ImportMahasiswa::class, 'import_id', 'import_id');
    }

    /**
     * Get the user who imported.
     */
    public function importer()
    {
        return $this->belongsTo(User::class, 'user_id_importer', 'id');
    }

    /**
     * Get the user who approved.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'user_id_approver', 'id');
    }

    /**
     * Get the SLTA.
     */
    public function slta()
    {
        return $this->belongsTo(Slta::class, 'slta_id', 'slta_id');
    }

    /**
     * Get the jalur daftar.
     */
    public function jalurDaftar()
    {
        return $this->belongsTo(JalurDaftar::class, 'jalurdaftar_id', 'jalurdaftar_id');
    }

    /**
     * Get the wilayah.
     */
    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id', 'wilayah_id');
    }
}
