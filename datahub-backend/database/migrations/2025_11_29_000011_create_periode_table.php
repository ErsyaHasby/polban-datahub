<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periode', function (Blueprint $table) {
            $table->id('periode_id');
            $table->integer('tahun_ajaran');

            // ENUM semester_enum
            $table->rawColumn('semester', 'semester_enum');

            $table->unique(['tahun_ajaran', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periode');
    }
};
