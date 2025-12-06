<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaNilai extends Model
{
    protected $table = 'mahasiswa_akademik';
    protected $primaryKey = 'mahasiswa_id';

    public $timestamps = false;

    protected $fillable = [
        'angkatan',
    ];

    public function nilaiMahasiswa()
    {
        return $this->hasMany(NilaiMahasiswa::class, 'mahasiswa_id', 'mahasiswa_id');
    }

    public function ip()
    {
        return $this->hasMany(Ip::class, 'mahasiswa_id', 'mahasiswa_id');
    }
}
