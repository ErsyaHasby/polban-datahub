<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    use HasFactory;

    protected $table = 'provinsi';
    protected $primaryKey = 'provinsi_id';

    protected $fillable = [
        'nama_provinsi',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'double',
        'longitude' => 'double',
    ];

    /**
     * Get all wilayah for this provinsi.
     */
    public function wilayahs()
    {
        return $this->hasMany(Wilayah::class, 'provinsi_id', 'provinsi_id');
    }
}
