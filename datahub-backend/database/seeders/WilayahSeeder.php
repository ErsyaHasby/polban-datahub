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
        // Data wilayah dengan provinsi
        $wilayahData = [
            // Sumatera Barat
            ['provinsi' => 'Sumatera Barat', 'nama_wilayah' => 'Kota Solok', 'latitude' => -0.8000, 'longitude' => 100.6590],
            ['provinsi' => 'Sumatera Barat', 'nama_wilayah' => 'Kota Bukit Tinggi', 'latitude' => -0.3051, 'longitude' => 100.3694],
            ['provinsi' => 'Sumatera Barat', 'nama_wilayah' => 'Kabupaten Pasaman', 'latitude' => 0.4167, 'longitude' => 100.2167],

            // Riau
            ['provinsi' => 'Riau', 'nama_wilayah' => 'Kota Pekanbaru', 'latitude' => 0.5071, 'longitude' => 101.4478],

            // Jambi
            ['provinsi' => 'Jambi', 'nama_wilayah' => 'Kota Jambi', 'latitude' => -1.6101, 'longitude' => 103.6131],

            // Sumatera Selatan
            ['provinsi' => 'Sumatera Selatan', 'nama_wilayah' => 'Kabupaten Muara Enim', 'latitude' => -3.8833, 'longitude' => 103.9333],

            // Lampung
            ['provinsi' => 'Lampung', 'nama_wilayah' => 'Kota Bandar Lampung', 'latitude' => -5.4500, 'longitude' => 105.2667],
            ['provinsi' => 'Lampung', 'nama_wilayah' => 'Kota Bandar Larnpung', 'latitude' => -5.4500, 'longitude' => 105.2667],

            // DKI Jakarta
            ['provinsi' => 'DKI Jakarta', 'nama_wilayah' => 'Jakarta Pusat', 'latitude' => -6.1757, 'longitude' => 106.8272],
            ['provinsi' => 'DKI Jakarta', 'nama_wilayah' => 'Jakarta Selatan', 'latitude' => -6.2833, 'longitude' => 106.7667],
            ['provinsi' => 'DKI Jakarta', 'nama_wilayah' => 'Jakarta Timur', 'latitude' => -6.2667, 'longitude' => 106.8667],
            ['provinsi' => 'DKI Jakarta', 'nama_wilayah' => 'Jakarta Barat', 'latitude' => -6.1333, 'longitude' => 106.7167],
            ['provinsi' => 'DKI Jakarta', 'nama_wilayah' => 'Jakarta Utara', 'latitude' => -6.0833, 'longitude' => 106.8500],

            // Jawa Barat
            ['provinsi' => 'Jawa Barat', 'nama_wilayah' => 'Kota Bandung', 'latitude' => -6.9271, 'longitude' => 107.6411],
            ['provinsi' => 'Jawa Barat', 'nama_wilayah' => 'Kota Cimahi', 'latitude' => -6.8900, 'longitude' => 107.5585],
            ['provinsi' => 'Jawa Barat', 'nama_wilayah' => 'Kota Bogor', 'latitude' => -6.5971, 'longitude' => 106.8060],
            ['provinsi' => 'Jawa Barat', 'nama_wilayah' => 'Kota Bekasi', 'latitude' => -6.2349, 'longitude' => 107.0051],
            ['provinsi' => 'Jawa Barat', 'nama_wilayah' => 'Kota Depok', 'latitude' => -6.4025, 'longitude' => 106.7942],
            ['provinsi' => 'Jawa Barat', 'nama_wilayah' => 'Kota Sukabumi', 'latitude' => -6.9278, 'longitude' => 106.9271],
            ['provinsi' => 'Jawa Barat', 'nama_wilayah' => 'Kota Tasikmalaya', 'latitude' => -7.3506, 'longitude' => 108.2050],
            ['provinsi' => 'Jawa Barat', 'nama_wilayah' => 'Kabupaten Bandung', 'latitude' => -7.0867, 'longitude' => 107.6053],
            ['provinsi' => 'Jawa Barat', 'nama_wilayah' => 'Kabupaten Bandung Barat', 'latitude' => -6.9667, 'longitude' => 107.3333],
            ['provinsi' => 'Jawa Barat', 'nama_wilayah' => 'Kabupaten Bogor', 'latitude' => -6.7000, 'longitude' => 106.9000],
            ['provinsi' => 'Jawa Barat', 'nama_wilayah' => 'Kabupaten Bekasi', 'latitude' => -6.3333, 'longitude' => 107.1667],
            ['provinsi' => 'Jawa Barat', 'nama_wilayah' => 'Kabupaten Garut', 'latitude' => -7.2167, 'longitude' => 107.9000],
            ['provinsi' => 'Jawa Barat', 'nama_wilayah' => 'Kabupaten Sukabumi', 'latitude' => -6.9278, 'longitude' => 106.9271],
            ['provinsi' => 'Jawa Barat', 'nama_wilayah' => 'Kabupaten Cianjur', 'latitude' => -6.8167, 'longitude' => 107.1333],
            ['provinsi' => 'Jawa Barat', 'nama_wilayah' => 'Kabupaten Purwakarta', 'latitude' => -6.5569, 'longitude' => 107.4431],
            ['provinsi' => 'Jawa Barat', 'nama_wilayah' => 'Kabupaten Subang', 'latitude' => -6.5667, 'longitude' => 107.7667],
            ['provinsi' => 'Jawa Barat', 'nama_wilayah' => 'Kabupaten Karawang', 'latitude' => -6.3063, 'longitude' => 107.3019],
            ['provinsi' => 'Jawa Barat', 'nama_wilayah' => 'Kabupaten Majalengka', 'latitude' => -6.8361, 'longitude' => 108.2278],
            ['provinsi' => 'Jawa Barat', 'nama_wilayah' => 'Kabupaten Tasikmalaya', 'latitude' => -7.7167, 'longitude' => 108.2000],
            ['provinsi' => 'Jawa Barat', 'nama_wilayah' => 'Kabupaten Cirebon', 'latitude' => -6.7333, 'longitude' => 108.5500],
            ['provinsi' => 'Jawa Barat', 'nama_wilayah' => 'Kabupaten Kuningan', 'latitude' => -6.9833, 'longitude' => 108.4833],
            ['provinsi' => 'Jawa Barat', 'nama_wilayah' => 'Kota Cilegon', 'latitude' => -6.0063, 'longitude' => 106.0194],

            // Banten
            ['provinsi' => 'Banten', 'nama_wilayah' => 'Kota Tangerang', 'latitude' => -6.1778, 'longitude' => 106.6333],
            ['provinsi' => 'Banten', 'nama_wilayah' => 'Kota Tangerang Selatan', 'latitude' => -6.3017, 'longitude' => 106.7250],
            ['provinsi' => 'Banten', 'nama_wilayah' => 'Kota Serang', 'latitude' => -6.1200, 'longitude' => 106.1500],
            ['provinsi' => 'Banten', 'nama_wilayah' => 'Kabupaten Lebak', 'latitude' => -6.5644, 'longitude' => 106.2522],
            ['provinsi' => 'Banten', 'nama_wilayah' => 'Kabupaten Serang', 'latitude' => -6.1200, 'longitude' => 106.1500],

            // Jawa Tengah
            ['provinsi' => 'Jawa Tengah', 'nama_wilayah' => 'Kota Semarang', 'latitude' => -7.2504, 'longitude' => 110.4119],
            ['provinsi' => 'Jawa Tengah', 'nama_wilayah' => 'Kota Tegal', 'latitude' => -6.8694, 'longitude' => 109.1402],
            ['provinsi' => 'Jawa Tengah', 'nama_wilayah' => 'Kabupaten Semarang', 'latitude' => -7.5000, 'longitude' => 110.5000],
            ['provinsi' => 'Jawa Tengah', 'nama_wilayah' => 'Kabupaten Klaten', 'latitude' => -7.7167, 'longitude' => 110.6000],
            ['provinsi' => 'Jawa Tengah', 'nama_wilayah' => 'Kabupaten Kudus', 'latitude' => -6.8050, 'longitude' => 110.8406],

            // DI Yogyakarta
            ['provinsi' => 'DI Yogyakarta', 'nama_wilayah' => 'Kabupaten Sleman', 'latitude' => -7.7167, 'longitude' => 110.3500],

            // Jawa Timur
            ['provinsi' => 'Jawa Timur', 'nama_wilayah' => 'Kota Surabaya', 'latitude' => -7.2504, 'longitude' => 112.7384],
            ['provinsi' => 'Jawa Timur', 'nama_wilayah' => 'Kota Blitar', 'latitude' => -8.0983, 'longitude' => 112.1681],
            ['provinsi' => 'Jawa Timur', 'nama_wilayah' => 'Kabupaten Sidoarjo', 'latitude' => -7.4405, 'longitude' => 112.6700],
            ['provinsi' => 'Jawa Timur', 'nama_wilayah' => 'Kabupaten Pamekasan', 'latitude' => -7.1581, 'longitude' => 113.4747],

            // Sumatera Utara
            ['provinsi' => 'Sumatera Utara', 'nama_wilayah' => 'Kabupaten Tapanuli Utara', 'latitude' => 2.0111, 'longitude' => 99.0667],

            // Papua
            ['provinsi' => 'Papua', 'nama_wilayah' => 'Kabupaten Fak-Fak', 'latitude' => -2.9167, 'longitude' => 132.3000],
        ];

        foreach ($wilayahData as $data) {
            $provinsi = DB::table('provinsi')->where('nama_provinsi', $data['provinsi'])->first();

            if ($provinsi) {
                DB::table('wilayah')->insert([
                    'provinsi_id' => $provinsi->provinsi_id,
                    'nama_wilayah' => $data['nama_wilayah'],
                    'latitude' => $data['latitude'],
                    'longitude' => $data['longitude'],
                ]);
            }
        }
    }
}
