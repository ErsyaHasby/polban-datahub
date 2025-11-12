<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slta extends Model
{
    use HasFactory;

    protected $table = 'slta';
    protected $primaryKey = 'slta_id';

    protected $fillable = [
        'nama_slta_resmi',
    ];

    /**
     * Get all mahasiswa from this SLTA.
     */
    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'slta_id', 'slta_id');
    }
}
