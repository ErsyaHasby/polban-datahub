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
        // Definisikan tipe ENUM kustom untuk PostgreSQL
        // Gunakan DO block untuk menghindari error jika type sudah ada
        DB::statement("DO $$ BEGIN
            CREATE TYPE import_status_enum AS ENUM ('pending', 'approved', 'rejected');
        EXCEPTION
            WHEN duplicate_object THEN null;
        END $$;");

        DB::statement("DO $$ BEGIN
            CREATE TYPE jenis_kelamin_enum AS ENUM ('L', 'P');
        EXCEPTION
            WHEN duplicate_object THEN null;
        END $$;");

        $agamas = "'Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu', 'Lainnya'";
        DB::statement("DO $$ BEGIN
            CREATE TYPE agama_enum AS ENUM ($agamas);
        EXCEPTION
            WHEN duplicate_object THEN null;
        END $$;");

        // Tabel Master/Lookup SLTA
        Schema::create('slta', function (Blueprint $table) {
            $table->id('slta_id');
            $table->string('nama_slta_resmi', 100)->unique();
            $table->timestamps();
        });

        // Tabel Master/Lookup Jalur Daftar
        Schema::create('jalur_daftar', function (Blueprint $table) {
            $table->id('jalurdaftar_id');
            $table->string('nama_jalur_daftar', 20)->unique();
            $table->timestamps();
        });

        // Tabel Master/Lookup Provinsi
        Schema::create('provinsi', function (Blueprint $table) {
            $table->id('provinsi_id');
            $table->string('nama_provinsi', 100)->unique();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->timestamps();
        });

        // Tabel Master/Lookup Wilayah (Kabupaten/Kota)
        Schema::create('wilayah', function (Blueprint $table) {
            $table->id('wilayah_id');
            $table->foreignId('provinsi_id')->constrained('provinsi', 'provinsi_id')->onDelete('restrict');
            $table->string('nama_wilayah', 100);
            $table->double('latitude');
            $table->double('longitude');
            $table->timestamps();

            $table->unique(['provinsi_id', 'nama_wilayah']); // Kombinasi harus unik
        });

        // Tabel Staging untuk impor data
        Schema::create('import_mahasiswa', function (Blueprint $table) {
            $table->id('import_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->rawColumn('status', 'import_status_enum')->default('pending');

            // Data mentah (semua nullable)
            $table->string('kelas', 2)->nullable();
            $table->integer('angkatan')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->rawColumn('jenis_kelamin', 'jenis_kelamin_enum')->nullable();
            $table->rawColumn('agama', 'agama_enum')->nullable();
            $table->string('kode_pos', 5)->nullable();
            $table->string('nama_slta_raw', 255)->nullable();
            $table->string('nama_jalur_daftar_raw', 20)->nullable();
            $table->string('nama_wilayah_raw', 100)->nullable(); // Kab/Kota
            $table->string('provinsi_raw', 255)->nullable(); // Provinsi
            $table->text('admin_notes')->nullable();

            $table->timestamps();
            $table->index('status');
        });

        // Tabel Final (data bersih)
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id('mahasiswa_id');

            // Kolom pelacakan (WAJIB)
            $table->foreignId('import_id')->constrained('import_mahasiswa', 'import_id')->onDelete('restrict');
            $table->foreignId('user_id_importer')->constrained('users')->onDelete('restrict');
            $table->foreignId('user_id_approver')->constrained('users')->onDelete('restrict');

            // Data (NULLABLE)
            $table->string('kelas', 2)->nullable();
            $table->integer('angkatan')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->rawColumn('jenis_kelamin', 'jenis_kelamin_enum')->nullable();
            $table->rawColumn('agama', 'agama_enum')->nullable();
            $table->string('kode_pos', 5)->nullable();

            // Foreign Keys (NULLABLE)
            $table->foreignId('slta_id')->nullable()->constrained('slta', 'slta_id')->onDelete('restrict');
            $table->foreignId('jalurdaftar_id')->nullable()->constrained('jalur_daftar', 'jalurdaftar_id')->onDelete('restrict');
            $table->foreignId('wilayah_id')->nullable()->constrained('wilayah', 'wilayah_id')->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
        Schema::dropIfExists('import_mahasiswa');
        Schema::dropIfExists('wilayah');
        Schema::dropIfExists('provinsi');
        Schema::dropIfExists('jalur_daftar');
        Schema::dropIfExists('slta');

        // Hapus ENUMs (kecuali role_enum yang ada di users table)
        DB::statement("DROP TYPE IF EXISTS import_status_enum");
        DB::statement("DROP TYPE IF EXISTS jenis_kelamin_enum");
        DB::statement("DROP TYPE IF EXISTS agama_enum");
    }
};
