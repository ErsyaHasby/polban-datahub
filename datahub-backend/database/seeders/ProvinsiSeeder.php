<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinsis = [
            ['nama_provinsi' => 'Jawa Barat', 'latitude' => -6.9175, 'longitude' => 107.6191],
            ['nama_provinsi' => 'DKI Jakarta', 'latitude' => -6.2088, 'longitude' => 106.8456],
            ['nama_provinsi' => 'Jawa Tengah', 'latitude' => -7.1506, 'longitude' => 110.1429],
            ['nama_provinsi' => 'Jawa Timur', 'latitude' => -7.5506, 'longitude' => 112.7520],
            ['nama_provinsi' => 'Banten', 'latitude' => -6.4058, 'longitude' => 106.0640],
        ];

        foreach ($provinsis as $provinsi) {
            DB::table('provinsi')->insert([
                'nama_provinsi' => $provinsi['nama_provinsi'],
                'latitude' => $provinsi['latitude'],
                'longitude' => $provinsi['longitude'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
