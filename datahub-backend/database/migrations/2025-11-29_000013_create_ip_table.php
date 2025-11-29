<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ip', function (Blueprint $table) {
            $table->id('ip_id');

            // FK mahasiswa
            $table->foreignId('mahasiswa_id')
                ->constrained('mahasiswa', 'mahasiswa_id')
                ->restrictOnDelete();

            // FK periode
            $table->foreignId('periode_id')
                ->constrained('periode', 'periode_id')
                ->restrictOnDelete();

            // Boleh NULL hanya ip_semester
            $table->decimal('ip_semester', 3, 2)->nullable();
            $table->decimal('ipk', 3, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ip');
    }
};
