<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slta extends Model
{
    use HasFactory;

    protected $table = 'slta';
    protected $primaryKey = 'slta_id';
    public $timestamps = false;

    protected $fillable = [
        'nama_slta',
    ];

    /**
     * Get all mahasiswa from this SLTA.
     */
    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'slta_id', 'slta_id');
    }
}
