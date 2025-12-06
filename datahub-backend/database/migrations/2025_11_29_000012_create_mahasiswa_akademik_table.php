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

            // FK TRACKING WAJIB
            $table->foreignId('import_akademik_id')
                ->nullable()
                ->constrained('import_mahasiswa_akademik', 'import_akademik_id')
                ->nullOnDelete();

            $table->foreignId('user_id_importer')
                ->nullable()
                ->constrained('users', 'user_id')
                ->nullOnDelete();

            $table->foreignId('user_id_approver')
                ->nullable()
                ->constrained('users', 'user_id')
                ->nullOnDelete();

            // DATA AKADEMIK
            $table->integer('angkatan');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_akademik');
    }
};
