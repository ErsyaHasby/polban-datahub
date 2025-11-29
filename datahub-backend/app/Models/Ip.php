<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ip extends Model
{
    protected $table = 'ip';
    protected $primaryKey = 'ip_id';

    public $timestamps = false;

    protected $fillable = [
        'mahasiswa_id',
        'periode_id',
        'ip_semester',
        'ipk',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaNilai::class, 'mahasiswa_id', 'mahasiswa_id');
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id', 'periode_id');
    }
}
