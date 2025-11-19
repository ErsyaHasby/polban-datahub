<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat Tabel untuk menyimpan Info File (Batch)
        Schema::create('import_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('file_path');     // Lokasi file di storage
            $table->string('original_name'); // Nama asli file (misal: data_mhs.xlsx)
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('admin_feedback')->nullable(); // Alasan reject/notes
            $table->timestamps();
        });

        // 2. Tambahkan Foreign Key ke tabel import_mahasiswa
        Schema::table('import_mahasiswa', function (Blueprint $table) {
            // Menambahkan kolom import_file_id agar setiap baris tahu dia milik file mana
            $table->foreignId('import_file_id')
                  ->nullable() // Nullable dulu biar tidak error data lama
                  ->after('id')
                  ->constrained('import_files')
                  ->onDelete('cascade'); // Jika file dihapus, data baris ikut terhapus
        });
    }

    public function down(): void
    {
        Schema::table('import_mahasiswa', function (Blueprint $table) {
            $table->dropForeign(['import_file_id']);
            $table->dropColumn('import_file_id');
        });
        Schema::dropIfExists('import_files');
    }
};