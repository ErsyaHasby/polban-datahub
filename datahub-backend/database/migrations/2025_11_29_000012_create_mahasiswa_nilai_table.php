<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai_mahasiswa', function (Blueprint $table) {
            $table->id('nilai_id');

            // FK mahasiswa
            $table->foreignId('mahasiswa_id')
                ->constrained('mahasiswa', 'mahasiswa_id')
                ->restrictOnDelete();

            // FK mata kuliah (string PK)
            $table->string('kode_mk', 8);
            $table->foreign('kode_mk')
                ->references('kode_mk')
                ->on('mata_kuliah')
                ->restrictOnDelete();

            // FK periode
            $table->foreignId('periode_id')
                ->constrained('periode', 'periode_id')
                ->restrictOnDelete();

            // ENUM nilai
            $table->rawColumn('nilai_huruf', 'nilai_huruf_enum');

            $table->unique(['mahasiswa_id', 'kode_mk', 'periode_id'], 'unique_nilai_per_periode');//tambahan
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_mahasiswa');
    }
};
