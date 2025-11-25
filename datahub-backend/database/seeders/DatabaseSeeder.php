<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat user admin
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@polban.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Buat user participant untuk testing
        DB::table('users')->insert([
            'name' => 'Participant',
            'email' => 'participant@polban.ac.id',
            'password' => Hash::make('password'),
            'role' => 'participant',
        ]);

        // Buat admin datacore
        DB::table('users')->insert([
            'name' => 'Admin Datacore',
            'email' => 'datacore@polban.ac.id',
            'password' => Hash::make('password'),
            'role' => 'datacore',
        ]);

        // Buat contoh mahasiswa yang login (internal)
        DB::table('users')->insert([
            'name' => 'Pengguna Dataview',
            'email' => 'mhs1@polban.ac.id',
            'password' => Hash::make('password'),
            'role' => 'dataview',
        ]);


        // Buat import_mahasiswa dummy untuk foreign key
        DB::table('import_mahasiswa')->insert([
            'user_id' => 1,
            'status' => 'approved',
        ]);

        // Panggil seeder data master (URUTAN PENTING!)
        $this->call([
            ProvinsiSeeder::class,         // Harus pertama
            WilayahSeeder::class,          // Harus setelah Provinsi
            SltaSeeder::class,             //
            JalurDaftarSeeder::class,      //
        ]);
    }
}
