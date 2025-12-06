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

            $table->integer('angkatan')->nullable();

            $table->text('admin_notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_mahasiswa_akademik');
    }
};
