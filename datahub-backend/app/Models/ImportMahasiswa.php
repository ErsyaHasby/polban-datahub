<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'import_mahasiswa';
    protected $primaryKey = 'import_id';

    protected $fillable = [
        'user_id',
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
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
    ];

    /**
     * Get the user who imported this data.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the approved mahasiswa record (if approved).
     */
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'import_id', 'import_id');
    }
}
