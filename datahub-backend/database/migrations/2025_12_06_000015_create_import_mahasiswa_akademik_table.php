<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_mahasiswa_akademik', function (Blueprint $table) {
            $table->id('import_akademik_id');

            $table->foreignId('user_id')
                ->constrained('users', 'user_id')
                ->onDelete('cascade');

            $table->uuid('batch_id')->index();
            $table->string('filename')->index();

            $table->rawColumn('status', 'import_status_enum')
                ->default('pending')
                ->index();

            // DATA MAHASISWA AKADEMIK
            $table->bigInteger('mahasiswa_id')->nullable();
            $table->integer('angkatan')->nullable();

            // DATA PERIODE
            $table->integer('tahun_ajaran')->nullable();
            $table->rawColumn('semester', 'semester_enum')->nullable();

            // DATA MATA KULIAH
            $table->string('kode_mk', 8)->nullable();
            $table->string('nama_mk', 127)->nullable();
            $table->integer('sks')->nullable();

            // DATA NILAI
            $table->rawColumn('nilai_huruf', 'nilai_huruf_enum')->nullable();

            // DATA IP
            $table->decimal('ip_semester', 3, 2)->nullable();
            $table->decimal('ipk', 3, 2)->nullable();

            $table->text('admin_notes')->nullable();
            $table->text('error_message')->nullable();

            $table->timestamps();

            // Index untuk query performa
            $table->index(['batch_id', 'status']);
            $table->index(['mahasiswa_id', 'tahun_ajaran', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_mahasiswa_akademik');
    }
};
