<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID provinsi
        $jabar = DB::table('provinsi')->where('nama_provinsi', 'Jawa Barat')->first();
        $dki = DB::table('provinsi')->where('nama_provinsi', 'DKI Jakarta')->first();
        $jateng = DB::table('provinsi')->where('nama_provinsi', 'Jawa Tengah')->first();
        $jatim = DB::table('provinsi')->where('nama_provinsi', 'Jawa Timur')->first();
        $banten = DB::table('provinsi')->where('nama_provinsi', 'Banten')->first();

        $wilayahs = [
            // Jawa Barat
            ['provinsi_id' => $jabar->provinsi_id, 'nama_wilayah' => 'Kota Bandung', 'latitude' => -6.9271, 'longitude' => 107.6411],
            ['provinsi_id' => $jabar->provinsi_id, 'nama_wilayah' => 'Kabupaten Bandung', 'latitude' => -7.0867, 'longitude' => 107.6053],
            ['provinsi_id' => $jabar->provinsi_id, 'nama_wilayah' => 'Kabupaten Bandung Barat', 'latitude' => -6.9667, 'longitude' => 107.3333],
            ['provinsi_id' => $jabar->provinsi_id, 'nama_wilayah' => 'Kota Cimahi', 'latitude' => -6.8900, 'longitude' => 107.5585],
            ['provinsi_id' => $jabar->provinsi_id, 'nama_wilayah' => 'Kota Bekasi', 'latitude' => -6.2349, 'longitude' => 107.0051],
            ['provinsi_id' => $jabar->provinsi_id, 'nama_wilayah' => 'Kabupaten Bekasi', 'latitude' => -6.3333, 'longitude' => 107.1667],
            ['provinsi_id' => $jabar->provinsi_id, 'nama_wilayah' => 'Kota Bogor', 'latitude' => -6.5971, 'longitude' => 106.8060],
            ['provinsi_id' => $jabar->provinsi_id, 'nama_wilayah' => 'Kabupaten Bogor', 'latitude' => -6.7000, 'longitude' => 106.9000],

            // DKI Jakarta
            ['provinsi_id' => $dki->provinsi_id, 'nama_wilayah' => 'Jakarta Pusat', 'latitude' => -6.1757, 'longitude' => 106.8272],
            ['provinsi_id' => $dki->provinsi_id, 'nama_wilayah' => 'Jakarta Selatan', 'latitude' => -6.2833, 'longitude' => 106.7667],
            ['provinsi_id' => $dki->provinsi_id, 'nama_wilayah' => 'Jakarta Timur', 'latitude' => -6.2667, 'longitude' => 106.8667],
            ['provinsi_id' => $dki->provinsi_id, 'nama_wilayah' => 'Jakarta Barat', 'latitude' => -6.1333, 'longitude' => 106.7167],
            ['provinsi_id' => $dki->provinsi_id, 'nama_wilayah' => 'Jakarta Utara', 'latitude' => -6.0833, 'longitude' => 106.8500],

            // Jawa Tengah
            ['provinsi_id' => $jateng->provinsi_id, 'nama_wilayah' => 'Kota Semarang', 'latitude' => -7.2504, 'longitude' => 110.4119],
            ['provinsi_id' => $jateng->provinsi_id, 'nama_wilayah' => 'Kabupaten Semarang', 'latitude' => -7.5000, 'longitude' => 110.5000],

            // Jawa Timur
            ['provinsi_id' => $jatim->provinsi_id, 'nama_wilayah' => 'Kota Surabaya', 'latitude' => -7.2504, 'longitude' => 112.7384],
            ['provinsi_id' => $jatim->provinsi_id, 'nama_wilayah' => 'Kabupaten Sidoarjo', 'latitude' => -7.4405, 'longitude' => 112.6700],

            // Banten
            ['provinsi_id' => $banten->provinsi_id, 'nama_wilayah' => 'Kota Tangerang', 'latitude' => -6.1778, 'longitude' => 106.6333],
            ['provinsi_id' => $banten->provinsi_id, 'nama_wilayah' => 'Kota Tangerang Selatan', 'latitude' => -6.3017, 'longitude' => 106.7250],
        ];

        foreach ($wilayahs as $wilayah) {
            DB::table('wilayah')->insert([
                'provinsi_id' => $wilayah['provinsi_id'],
                'nama_wilayah' => $wilayah['nama_wilayah'],
                'latitude' => $wilayah['latitude'],
                'longitude' => $wilayah['longitude'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
