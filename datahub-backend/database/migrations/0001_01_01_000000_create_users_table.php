<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create role enum first (if using PostgreSQL)
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("DO $$ BEGIN
                CREATE TYPE role_enum AS ENUM ('admin', 'participant');
            EXCEPTION
                WHEN duplicate_object THEN null;
            END $$;");
        }

        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('name', 255);
            $table->string('email', 255)->unique();
            $table->string('password', 255);

            // Add role column
            if (DB::getDriverName() === 'pgsql') {
                $table->rawColumn('role', 'role_enum')->default('participant');
            } else {
                $table->enum('role', ['admin', 'participant'])->default('participant');
            }
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');

        // Drop role enum if using PostgreSQL
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("DROP TYPE IF EXISTS role_enum CASCADE");
        }
    }
};
