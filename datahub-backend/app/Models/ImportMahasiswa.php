<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportMahasiswa extends Model
{
    protected $table = 'import_mahasiswa';
    protected $primaryKey = 'import_id';
    
    protected $fillable = [
        'user_id',
        'batch_id',     
        'filename',   
        'status',
        'kelas',
        'angkatan',
        'tgl_lahir',
        'jenis_kelamin',
        'agama',
        'kode_pos',
        'nama_slta_raw',
        'nama_jalur_daftar_raw',
        'nama_wilayah_raw',
        'provinsi_raw',
        'admin_notes',
        'created_at',   
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}