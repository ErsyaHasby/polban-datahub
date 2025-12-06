<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiMahasiswa extends Model
{
    protected $table = 'nilai_mahasiswa';
    protected $primaryKey = 'nilai_id';

    public $timestamps = false;

    protected $fillable = [
        'mahasiswa_id',
        'kode_mk',
        'periode_id',
        'nilai_huruf',
    ];

    // Relasi ke mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaNilai::class, 'mahasiswa_id', 'mahasiswa_id');
    }

    // Relasi ke mata kuliah
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'kode_mk', 'kode_mk');
    }

    // Relasi ke periode
    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id', 'periode_id');
    }
}
