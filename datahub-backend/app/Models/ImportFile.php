<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportFile extends Model
{
    use HasFactory;

    protected $table = 'import_files';

    protected $fillable = [
        'user_id',
        'file_path',
        'original_name',
        'status',
        'admin_feedback'
    ];

    // Relasi: Satu File punya banyak Baris Data Mahasiswa
    public function importRows()
    {
        return $this->hasMany(ImportMahasiswa::class, 'import_file_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}