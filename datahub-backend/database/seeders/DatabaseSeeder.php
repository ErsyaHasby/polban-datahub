<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // <--- Wajib di-import untuk generate UUID

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User Admin
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@polban.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Buat User Participant
        DB::table('users')->insert([
            'name' => 'Participant',
            'email' => 'participant@polban.ac.id',
            'password' => Hash::make('password'),
            'role' => 'participant',
        ]);

        // 3. Buat User Datacore
        DB::table('users')->insert([
            'name' => 'Admin Datacore',
            'email' => 'datacore@polban.ac.id',
            'password' => Hash::make('password'),
            'role' => 'datacore',
        ]);

        // 4. Buat User Dataview (Internal)
        DB::table('users')->insert([
            'name' => 'Pengguna Dataview',
            'email' => 'mhs1@polban.ac.id',
            'password' => Hash::make('password'),
            'role' => 'dataview',
        ]);

        // 5. Buat Dummy Import Data (Fix Error Batch ID disini)
        DB::table('import_mahasiswa')->insert([
            'user_id' => 1, // Milik Admin
            'batch_id' => (string) Str::uuid(), // <--- Generate UUID batch unik
            'filename' => 'data_awal_dummy.xlsx', // <--- Nama file dummy
            'status' => 'approved',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 6. Panggil Seeder Data Master (Pastikan file-file ini ada)
        $this->call([
            ProvinsiSeeder::class,
            WilayahSeeder::class,
            SltaSeeder::class,
            JalurDaftarSeeder::class,
        ]);
    }
}