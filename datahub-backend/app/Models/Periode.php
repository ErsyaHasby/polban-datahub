<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $table = 'periode';
    protected $primaryKey = 'periode_id';

    public $timestamps = false;

    protected $fillable = [
        'tahun_ajaran',
        'semester',
    ];

    public function nilaiMahasiswa()
    {
        return $this->hasMany(NilaiMahasiswa::class, 'periode_id', 'periode_id');
    }

    public function ip()
    {
        return $this->hasMany(Ip::class, 'periode_id', 'periode_id');
    }
}
