<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ENUM untuk role user
        DB::statement("DROP TYPE IF EXISTS role_enum CASCADE");
        DB::statement("CREATE TYPE role_enum AS ENUM ('admin', 'participant', 'datacore', 'dataview')");

        // ENUM untuk status impor
        DB::statement("DO $$ BEGIN
            CREATE TYPE import_status_enum AS ENUM ('pending', 'approved', 'rejected');
        EXCEPTION
            WHEN duplicate_object THEN null;
        END $$;");

        // ENUM jenis kelamin
        DB::statement("DO $$ BEGIN
            CREATE TYPE jenis_kelamin_enum AS ENUM ('L', 'P');
        EXCEPTION
            WHEN duplicate_object THEN null;
        END $$;");

        // ENUM agama
        $agamas = "'Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu', 'Lainnya'";
        DB::statement("DO $$ BEGIN
            CREATE TYPE agama_enum AS ENUM ($agamas);
        EXCEPTION
            WHEN duplicate_object THEN null;
        END $$;");

        // ENUM untuk activity log
        $actions = "'login', 'logout', 'login_failed', 'import_data', 'export_batch', 'approve_data', 'reject_data', 'create_user', 'update_user', 'approve_batch', 
            'reject_batch','get_mahasiswa_data', 'get_akademik_data'";
        DB::statement("DO $$ BEGIN
            CREATE TYPE action_log_enum AS ENUM ($actions);
        EXCEPTION
            WHEN duplicate_object THEN null;
        END $$;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP TYPE IF EXISTS action_log_enum");
        DB::statement("DROP TYPE IF EXISTS agama_enum");
        DB::statement("DROP TYPE IF EXISTS jenis_kelamin_enum");
        DB::statement("DROP TYPE IF EXISTS import_status_enum");
        DB::statement("DROP TYPE IF EXISTS role_enum");
    }
};
