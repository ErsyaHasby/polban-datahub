<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa_akademik', function (Blueprint $table) {
            $table->bigIncrements('mahasiswa_id'); // PK
            $table->integer('angkatan'); // wajib diisi
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_akademik');
    }
};
