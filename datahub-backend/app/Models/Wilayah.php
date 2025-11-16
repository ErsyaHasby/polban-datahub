<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;

    protected $table = 'wilayah';
    protected $primaryKey = 'wilayah_id';
    public $timestamps = false;

    protected $fillable = [
        'provinsi_id',
        'nama_wilayah',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'double',
        'longitude' => 'double',
    ];

    /**
     * Get the provinsi that owns this wilayah.
     */
    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id', 'provinsi_id');
    }

    /**
     * Get all mahasiswa from this wilayah.
     */
    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'wilayah_id', 'wilayah_id');
    }
}
