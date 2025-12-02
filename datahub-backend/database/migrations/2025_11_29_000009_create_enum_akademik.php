<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ENUM semester: Ganjil, Genap
        DB::statement("DROP TYPE IF EXISTS semester_enum");
        DB::statement("CREATE TYPE semester_enum AS ENUM ('Ganjil', 'Genap')");

        // ENUM nilai huruf
        DB::statement("DROP TYPE IF EXISTS nilai_huruf_enum");
        DB::statement("
            CREATE TYPE nilai_huruf_enum AS ENUM
            ('A', 'AB', 'B', 'BC', 'C', 'CD', 'D')
        ");
    }
};
