<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JalurDaftar extends Model
{
    use HasFactory;

    protected $table = 'jalur_daftar';
    protected $primaryKey = 'jalurdaftar_id';

    protected $fillable = [
        'nama_jalur_daftar',
    ];

    /**
     * Get all mahasiswa from this jalur daftar.
     */
    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'jalurdaftar_id', 'jalurdaftar_id');
    }
}
