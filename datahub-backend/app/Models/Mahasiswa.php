<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

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
        'jalur_daftar_id',
        'wilayah_id',
    ];

    protected $casts = [
        'jenis_kelamin' => 'string',
        'agama'         => 'string',
        'tgl_lahir'     => 'date',
        'angkatan'      => 'integer',
    ];

    public function import()
    {
        return $this->belongsTo(ImportMahasiswa::class, 'import_id', 'import_id');
    }

    public function importer()
    {
        return $this->belongsTo(User::class, 'user_id_importer', 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'user_id_approver', 'user_id');
    }

    public function slta()
    {
        return $this->belongsTo(Slta::class, 'slta_id', 'slta_id');
    }

    public function jalurDaftar()
    {
        return $this->belongsTo(JalurDaftar::class, 'jalur_daftar_id', 'jalur_daftar_id');
    }

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id', 'wilayah_id');
    }

    public function provinsi(): HasOneThrough
    {
        return $this->hasOneThrough(
            Provinsi::class,
            Wilayah::class,
            "wilayah_id",
            "provinsi_id",
            "wilayah_id",
            "provinsi_id",
        );
    }
}
