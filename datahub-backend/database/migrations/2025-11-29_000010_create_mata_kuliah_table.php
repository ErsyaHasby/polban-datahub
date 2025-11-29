<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->string('kode_mk', 8)->primary();
            $table->string('nama_mk', 50);
            $table->integer('sks');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah');
    }
};
