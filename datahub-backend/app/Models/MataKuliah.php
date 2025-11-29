<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliah';
    protected $primaryKey = 'kode_mk';
    public $incrementing = false;     // PK adalah string
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
    ];

    // Relasi ke nilai mahasiswa
    public function nilaiMahasiswa()
    {
        return $this->hasMany(NilaiMahasiswa::class, 'kode_mk', 'kode_mk');
    }
}
