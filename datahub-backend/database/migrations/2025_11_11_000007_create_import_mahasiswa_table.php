<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_mahasiswa', function (Blueprint $table) {
            $table->id('import_id');

            // FK ke users (importer)
            $table->foreignId('user_id')
                ->constrained('users', 'user_id')
                ->onDelete('cascade');

            // --- KOLOM BARU UNTUK GROUPING FILE ---
            $table->uuid('batch_id')->index(); // Penanda unik per upload file
            $table->string('filename')->index(); // Nama file asli
            // -------------------------------------

            // Status berlaku per baris, tapi nanti diupdate massal per batch_id
            $table->rawColumn('status', 'import_status_enum')
                ->default('pending')
                ->index();

            // RAW DATA
            $table->string('kelas', 10)->nullable();
            $table->integer('angkatan')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('jenis_kelamin', 20)->nullable();
            $table->string('agama', 20)->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('nama_slta_raw', 255)->nullable();
            $table->string('nama_jalur_daftar_raw', 100)->nullable();
            $table->string('nama_wilayah_raw', 100)->nullable();
            $table->string('provinsi_raw', 255)->nullable();
            
            $table->text('admin_notes')->nullable();
            
            $table->timestamps(); // Penting untuk sort berdasarkan waktu upload
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_mahasiswa');
    }
};