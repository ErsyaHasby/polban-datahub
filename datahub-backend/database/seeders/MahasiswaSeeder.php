<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data mahasiswa lengkap 253 records dari CSV
     */
    public function run(): void
    {
        // Baca mapping data dari database untuk foreign keys
        $sltaMap = [];
        foreach (DB::table('slta')->get() as $slta) {
            $sltaMap[$slta->nama_slta] = $slta->slta_id;
        }

        $jalurDaftarMap = [];
        foreach (DB::table('jalur_daftar')->get() as $jalur) {
            $jalurDaftarMap[$jalur->nama_jalur_daftar] = $jalur->jalur_daftar_id;
        }

        $wilayahMap = [];
        foreach (DB::table('wilayah')->get() as $wilayah) {
            $wilayahMap[$wilayah->nama_wilayah] = $wilayah->wilayah_id;
        }

        // Data lengkap 253 mahasiswa dari CSV (hardcoded)
        $mahasiswaData = [
            // Angkatan 2023 - Kelas 1A (32 mahasiswa)
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2005-10-16', 'slta' => 'SMA LAIN-LAIN', 'jalur' => 'SNBT', 'wilayah' => 'Kota Solok', 'kode_pos' => '27778', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2005-05-18', 'slta' => 'SMA 1 CILEUNGSI', 'jalur' => 'SMBM-TES', 'wilayah' => 'Kabupaten Bogor', 'kode_pos' => '16820', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2005-01-26', 'slta' => 'SMAN 4 CIMAHI', 'jalur' => 'SMBM-UTBK', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40531', 'jk' => 'P', 'agama' => 'Kristen'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2005-01-11', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40535', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2004-05-09', 'slta' => 'SMAN 1 BALEENDAH', 'jalur' => 'SNBP', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40375', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2004-09-15', 'slta' => 'SMA LABORATORIUM UPI', 'jalur' => 'SNBT', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40511', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2004-12-19', 'slta' => 'SMKN 1 KOTA BLITAR', 'jalur' => 'SNBT', 'wilayah' => 'Kota Blitar', 'kode_pos' => '66117', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2005-02-19', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SMBM-TES', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40526', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2005-11-16', 'slta' => 'SMAN 5 BUKITTINGGI', 'jalur' => 'SNBT', 'wilayah' => null, 'kode_pos' => '', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2004-11-16', 'slta' => 'SMAN 19 BANDUNG', 'jalur' => 'SMBM-TES', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40191', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2004-08-18', 'slta' => 'SMAN 1 CILEUNYI', 'jalur' => 'SMBM-TES', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40624', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2005-05-30', 'slta' => 'SMAN 2 TAROGONG KIDUL / SMAN 6 GARUT', 'jalur' => 'SMBM-UTBK', 'wilayah' => 'Kabupaten Garut', 'kode_pos' => '44116', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2004-11-02', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40135', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2005-02-25', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBT', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40526', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2005-11-12', 'slta' => 'MAN 2 PEKANBARU', 'jalur' => 'SMBM-UTBK', 'wilayah' => 'Kota Pekanbaru', 'kode_pos' => '28284', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2005-01-23', 'slta' => 'SMKN 11 BANDUNG', 'jalur' => 'SNBP', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40151', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2003-09-30', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SMBM-TES', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40513', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2005-06-28', 'slta' => 'SMAN 1 Padalarang', 'jalur' => 'SMBM-UTBK', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40553', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2004-09-18', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SMBM-TES', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40551', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2004-06-15', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40559', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2005-06-22', 'slta' => 'SMAN 1 CICALENGKA', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40395', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2004-12-29', 'slta' => 'SMAN 22 BANDUNG', 'jalur' => 'SMBM-UTBK', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40295', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2004-01-25', 'slta' => 'SMAN 1 PALABUHAN RATU', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Sukabumi', 'kode_pos' => '43364', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2005-03-02', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40513', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2004-12-03', 'slta' => 'SMKN 1 PURWAKARTA', 'jalur' => 'SMBM-UTBK', 'wilayah' => 'Kabupaten Purwakarta', 'kode_pos' => '41151', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2005-08-03', 'slta' => 'SMKN 4 BANDUNG', 'jalur' => 'SNBP', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40242', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2005-09-06', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBT', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40513', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2005-11-07', 'slta' => 'MAS MULTI TEKNIK ASIH PUTERA CIMAHI', 'jalur' => 'SMBM-UTBK', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40535', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2005-10-23', 'slta' => 'MAN SUBANG', 'jalur' => 'SNBP', 'wilayah' => 'Kabupaten Subang', 'kode_pos' => '41251', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2005-03-28', 'slta' => 'SMAN 1 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40162', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2005-11-06', 'slta' => 'SMA LAIN-LAIN', 'jalur' => 'SMBM-UTBK', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40559', 'jk' => 'L', 'agama' => 'Kristen'],
            ['kelas' => '1A', 'angkatan' => 2023, 'tgl_lahir' => '2003-10-07', 'slta' => 'SMAN 1 SUBANG', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Subang', 'kode_pos' => '41211', 'jk' => 'L', 'agama' => 'Islam'],

            // Angkatan 2023 - Kelas 1B
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2004-09-19', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40522', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2005-03-11', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40511', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2004-08-21', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBT', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40523', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2005-07-16', 'slta' => 'SMAN 13 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40535', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2004-04-01', 'slta' => 'SMAN 1 MAJALENGKA', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Majalengka', 'kode_pos' => '45411', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2005-08-18', 'slta' => 'SMAN 5 CIMAHI', 'jalur' => 'SNBT', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40524', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2005-08-08', 'slta' => 'SMKN 4 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40624', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2004-04-07', 'slta' => 'SMAN 5 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40523', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2003-11-13', 'slta' => 'SMKN 11 BANDUNG', 'jalur' => 'SMBM-UTBK', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40183', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2005-10-31', 'slta' => 'SMAN 14 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40295', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2004-06-12', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40526', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2005-04-21', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40513', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2005-02-27', 'slta' => 'SMAN 14 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40375', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2004-02-12', 'slta' => 'SMAN 3 KLATEN', 'jalur' => 'SMBM-TES', 'wilayah' => 'Kabupaten Klaten', 'kode_pos' => '57438', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2004-12-19', 'slta' => '', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Garut', 'kode_pos' => '44186', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2005-05-12', 'slta' => 'SMAN 1 BALEENDAH', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40376', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2004-10-11', 'slta' => 'SMAN 1 PANGALENGAN', 'jalur' => 'SMBM-UTBK', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40378', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2004-08-09', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40513', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2005-10-17', 'slta' => 'SMAN 1 RANGKASBITUNG', 'jalur' => 'SMBM-UTBK', 'wilayah' => 'Kabupaten Lebak', 'kode_pos' => '42312', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2005-09-04', 'slta' => '', 'jalur' => 'SMBM-UTBK', 'wilayah' => 'Kabupaten Purwakarta', 'kode_pos' => '41115', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2005-03-07', 'slta' => 'SMAN 2 CIMAHI', 'jalur' => 'SMBM-UTBK', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40523', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2005-09-13', 'slta' => 'SMAN 13 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40535', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2002-08-30', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SMBM-TES', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40561', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2005-09-23', 'slta' => 'MAN SUBANG', 'jalur' => 'SMBM-TES', 'wilayah' => 'Kabupaten Subang', 'kode_pos' => '41285', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2005-03-06', 'slta' => '', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40291', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2005-09-30', 'slta' => 'SMA NEGERI 1 TALANG UBI', 'jalur' => 'SNBP', 'wilayah' => 'Kabupaten Muara Enim', 'kode_pos' => '31214', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2004-01-28', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40552', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2005-03-28', 'slta' => 'SMA LAIN-LAIN', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40375', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2005-08-31', 'slta' => 'SMAN 1 TAROGONG KIDUL GARUT/SMAN 1 GARUT', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Garut', 'kode_pos' => '44119', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2004-11-13', 'slta' => 'MA PERSIS 3 PAMEUNGPEUK', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40375', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2004-03-22', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBT', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40535', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2023, 'tgl_lahir' => '2003-02-22', 'slta' => 'SMKN 2 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40153', 'jk' => 'L', 'agama' => 'Islam'],

            // Angkatan 2023 - Kelas 1C
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2003-11-27', 'slta' => 'SMAN 8 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40258', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2005-04-24', 'slta' => 'SMKN 2 BANDUNG', 'jalur' => 'SMBM-TES', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40153', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2004-06-04', 'slta' => 'SMA PLUS MERDEKA SOREANG', 'jalur' => 'SNBP', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40914', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2005-08-24', 'slta' => 'SMA PASUNDAN 8', 'jalur' => 'SMBM-TES', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40559', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2005-03-15', 'slta' => 'SMAN 1 Ngamprah', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40552', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2004-11-06', 'slta' => 'SMAN 1 TAROGONG KIDUL GARUT/SMAN 1 GARUT', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Garut', 'kode_pos' => '44151', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2003-11-30', 'slta' => 'SMAN 5 PAMEKASAN', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Pamekasan', 'kode_pos' => '69382', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2005-11-21', 'slta' => 'SMAN 1 MARGAHAYU', 'jalur' => 'SNBP', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40228', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2005-04-27', 'slta' => 'SMA PLUS ASSALAAM DAYEUHKOLOT', 'jalur' => 'SMBM-TES', 'wilayah' => null, 'kode_pos' => '', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2005-04-02', 'slta' => 'SMKN 11 BANDUNG', 'jalur' => 'SMBM-TES', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40561', 'jk' => 'P', 'agama' => 'Katolik'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2005-08-08', 'slta' => 'SMAN 11 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40243', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2004-06-28', 'slta' => 'SMKN 2 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40134', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2005-02-19', 'slta' => 'SMAN 5 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40524', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2005-03-21', 'slta' => 'SMAN 1 PLUMBON', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Cirebon', 'kode_pos' => '45154', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2004-12-26', 'slta' => 'SMA SUMATERA 40 NO. 2', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40271', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2005-05-02', 'slta' => 'SMAN 5 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40522', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2005-06-05', 'slta' => 'SMAN 1 MARGAHAYU', 'jalur' => 'SNBP', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40229', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2005-01-19', 'slta' => 'SMAN 2 CIMAHI', 'jalur' => 'SMBM-TES', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40513', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2004-06-22', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBT', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40512', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2005-06-09', 'slta' => 'SMAN 2 KUDUS', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Kudus', 'kode_pos' => '59332', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2004-05-30', 'slta' => 'SMAN 1 MAJALENGKA', 'jalur' => 'SMBM-TES', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40153', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2004-12-26', 'slta' => 'SMKN 4 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40287', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2004-08-19', 'slta' => 'SMAN 13 BANDUNG', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40534', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2004-03-03', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40216', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2005-02-02', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SMBM-TES', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40522', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2005-09-17', 'slta' => 'MAN 1 BOGOR', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bogor', 'kode_pos' => '16161', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2005-12-31', 'slta' => 'SMAN 1 LELES / SMAN 2 GARUT', 'jalur' => 'SMBM-TES', 'wilayah' => 'Kabupaten Garut', 'kode_pos' => '44152', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2005-02-25', 'slta' => 'SMA ISLAM CIPASUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Tasikmalaya', 'kode_pos' => '46462', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2005-07-27', 'slta' => 'SMEA Muh. Sragen', 'jalur' => 'ADIK', 'wilayah' => 'Kabupaten Fak-Fak', 'kode_pos' => '98611', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2004-04-22', 'slta' => 'SMA LAIN-LAIN', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40121', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2005-03-28', 'slta' => 'SMKN 11 BANDUNG', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40535', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2023, 'tgl_lahir' => '2005-07-12', 'slta' => 'SMAN 3 TEGAL', 'jalur' => 'SNBT', 'wilayah' => 'Kota Tegal', 'kode_pos' => '52125', 'jk' => 'P', 'agama' => 'Islam'],

            // Angkatan 2024 - Kelas 1A (32 mahasiswa)
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2005-12-11', 'slta' => 'SMKN 13 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40626', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2006-11-24', 'slta' => 'SMAN 1 SUBANG', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Subang', 'kode_pos' => '41252', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2006-07-19', 'slta' => 'SMAN 2 KS', 'jalur' => 'SNBT', 'wilayah' => 'Kota Cilegon', 'kode_pos' => '42434', 'jk' => 'L', 'agama' => 'Katolik'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2006-01-03', 'slta' => 'SMA ANGKASA 1', 'jalur' => 'SNBT', 'wilayah' => 'Jakarta Timur', 'kode_pos' => '13450', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2005-12-29', 'slta' => 'SMAN 1 KATAPANG', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40921', 'jk' => 'L', 'agama' => 'Katolik'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2005-07-28', 'slta' => 'SMAN 13 BANDUNG', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40535', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2006-08-21', 'slta' => 'SMAN 22 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40275', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2006-01-10', 'slta' => 'SMK (STM) N 5 SEMARANG', 'jalur' => 'SNBT', 'wilayah' => 'Kota Semarang', 'kode_pos' => '50165', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2006-11-11', 'slta' => 'SMAN 1 MARGAHAYU', 'jalur' => 'SNBP', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40228', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2006-02-15', 'slta' => 'SMK TI PEMBANGUNAN CIMAHI', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40559', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2006-01-26', 'slta' => 'MA LAIN-LAIN', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40234', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2006-05-21', 'slta' => 'SMAN 1 BALEENDAH', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40375', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2006-03-05', 'slta' => 'SMAN 1 Batujajar', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40767', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2006-12-28', 'slta' => 'SMAN 2 TARUTUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Tapanuli Utara', 'kode_pos' => '22452', 'jk' => 'L', 'agama' => 'Kristen'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2005-03-27', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40552', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2005-08-28', 'slta' => 'SMAN 2 TAROGONG KIDUL / SMAN 6 GARUT', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Garut', 'kode_pos' => '44151', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2004-11-22', 'slta' => 'SMAN 1 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40283', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2006-01-18', 'slta' => 'MAN 2 TANJUNG KARANG', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandar Larnpung', 'kode_pos' => '35134', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2006-05-03', 'slta' => 'SMA LAIN-LAIN', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Majalengka', 'kode_pos' => '45411', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2006-03-18', 'slta' => 'SMA LAIN-LAIN', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Karawang', 'kode_pos' => '41361', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2006-01-30', 'slta' => 'SMKN 4 BANDUNG', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40233', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2007-07-04', 'slta' => 'SMAN 1 KOTA SERANG', 'jalur' => 'SNBP', 'wilayah' => 'Kota Serang', 'kode_pos' => '42114', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2006-05-16', 'slta' => 'MAN 1 KOTA BANDUNG', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40534', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2006-10-17', 'slta' => 'SMKN 11 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40212', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2006-08-23', 'slta' => 'SMAN 6 CIMAHI', 'jalur' => 'SNBT', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40534', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2005-04-08', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40526', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2005-12-05', 'slta' => 'SMKN 13 BANDUNG', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40272', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2005-05-17', 'slta' => 'SMAN 1 TAROGONG KIDUL GARUT/SMAN 1 GARUT', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Garut', 'kode_pos' => '44151', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2005-11-26', 'slta' => 'SMAN 1 DEPOK', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Depok', 'kode_pos' => '16422', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2006-02-16', 'slta' => 'SMAN 12 BANDUNG', 'jalur' => 'SNBP', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40286', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2006-03-06', 'slta' => 'SMK TI PEMBANGUNAN CIMAHI', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40522', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2024, 'tgl_lahir' => '2006-06-13', 'slta' => 'SMA LAIN-LAIN', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Bekasi', 'kode_pos' => '17530', 'jk' => 'L', 'agama' => 'Islam'],

            // Angkatan 2024 - Kelas 1B
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2006-01-06', 'slta' => 'SMA LAIN-LAIN', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bekasi', 'kode_pos' => '17519', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2006-06-16', 'slta' => 'SMAN 14 BANDUNG', 'jalur' => 'SNBP', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40375', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2006-03-22', 'slta' => 'SMA LAIN-LAIN', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40559', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2005-04-10', 'slta' => 'SMKN 2 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40296', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2005-11-30', 'slta' => 'SMA NEGERI 11', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Sleman', 'kode_pos' => '55285', 'jk' => 'L', 'agama' => 'Katolik'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2005-08-15', 'slta' => 'SMAN 1 BANJARAN', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40377', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2006-01-23', 'slta' => 'SMAN 17 BANDUNG', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40124', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2006-10-11', 'slta' => 'SMAN 3 CIMAHI', 'jalur' => 'SNBT', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40513', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2006-04-28', 'slta' => 'SMKN 13 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40293', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2006-03-28', 'slta' => 'SMK LAIN-LAIN', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40921', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2005-06-03', 'slta' => '', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Garut', 'kode_pos' => '44150', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2005-12-01', 'slta' => 'MA LAIN-LAIN', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Pasaman', 'kode_pos' => '26355', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2006-06-14', 'slta' => 'SMAN 24 BANDUNG', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40624', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2005-06-24', 'slta' => 'SMAN 1 BANDUNG', 'jalur' => 'SNBP', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40133', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2006-02-04', 'slta' => 'SMAN 22 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40287', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2006-04-08', 'slta' => 'SMAN 1 CIWIDEY', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40972', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2004-05-27', 'slta' => 'MAN 1 KOTA BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40552', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2005-11-21', 'slta' => 'SMAN 19 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40135', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2005-10-07', 'slta' => 'MAN 2 KOTA BANDUNG', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40615', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2005-09-22', 'slta' => 'SMKN 13 BANDUNG', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40626', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2005-10-24', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '50521', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2006-06-19', 'slta' => 'SMAN 23 BANDUNG', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40291', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2005-10-04', 'slta' => 'SMK ANGKASA', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40213', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2006-01-25', 'slta' => 'SMKN 4 BANDUNG', 'jalur' => 'SNBP', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40224', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2005-08-12', 'slta' => 'SMA LAIN-LAIN', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Serang', 'kode_pos' => '42186', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2005-10-31', 'slta' => 'SMA NEGERI 2 LEMBANG', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40391', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2006-02-13', 'slta' => 'SMK TI PEMBANGUNAN CIMAHI', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40535', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2005-12-03', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40524', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2004-10-26', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40522', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2006-06-11', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40265', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2006-04-28', 'slta' => 'SMAN 1 Cipatat', 'jalur' => 'SNBP', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40554', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2024, 'tgl_lahir' => '2006-02-28', 'slta' => 'SMAN 1 MARGAHAYU', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40218', 'jk' => 'P', 'agama' => 'Islam'],

            // Angkatan 2024 - Kelas 1C
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2007-06-09', 'slta' => 'MA LAIN-LAIN', 'jalur' => 'SNBT', 'wilayah' => 'Kota Pekanbaru', 'kode_pos' => '28293', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2004-06-30', 'slta' => 'SMKN 4 Padalarang', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40553', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2006-06-16', 'slta' => 'SMAN 1 Ngamprah', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40559', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2006-06-18', 'slta' => 'SMAN 1 PLUMBON', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Cirebon', 'kode_pos' => '45154', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2006-04-16', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40533', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2006-10-10', 'slta' => 'SMAN 1 TAROGONG KIDUL GARUT/SMAN 1 GARUT', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Garut', 'kode_pos' => '44161', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2006-03-01', 'slta' => 'SMA MUHAMMADIYAH 4', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40624', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2006-01-04', 'slta' => 'SMKN 11 BANDUNG', 'jalur' => 'SNBP', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40559', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2005-10-08', 'slta' => 'MAN 2 KOTA BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40624', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2006-01-02', 'slta' => 'SMAN 1 Ngamprah', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40552', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2006-07-14', 'slta' => 'SMAN 2 BANDUNG', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40131', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2006-01-29', 'slta' => 'SMAN 1 LEMAHABANG', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Cirebon', 'kode_pos' => '45183', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2005-06-14', 'slta' => 'SMAN 1 TAROGONG KIDUL GARUT/SMAN 1 GARUT', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Garut', 'kode_pos' => '44191', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2005-05-26', 'slta' => 'SMA LAIN-LAIN', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40375', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2006-03-08', 'slta' => 'SMKN PALASAH', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Majalengka', 'kode_pos' => '45456', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2004-11-16', 'slta' => 'SMK ICB CINTA NIAGA SMEA', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40125', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2005-12-19', 'slta' => 'SMK LAIN-LAIN', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40394', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2005-08-06', 'slta' => 'SMAN 1 Cililin', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40562', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2006-09-30', 'slta' => 'MAS HUSNUL KHOTIMAH', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandar Larnpung', 'kode_pos' => '35122', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2005-02-08', 'slta' => 'SMAN 11 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40243', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2006-06-25', 'slta' => 'SMAN 1 Cisarua', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40552', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2006-03-27', 'slta' => 'SMAN 5 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40526', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2006-02-19', 'slta' => 'SMAN 7 BANDUNG', 'jalur' => 'SNBP', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40261', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2005-03-15', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40521', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2005-12-29', 'slta' => 'SMAN 1 MARGAHAYU', 'jalur' => 'SNBP', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40973', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2004-12-04', 'slta' => 'SMA LAIN-LAIN', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Garut', 'kode_pos' => '44191', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2006-03-10', 'slta' => 'SMAN 2 KS', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Cilegon', 'kode_pos' => '42433', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2006-04-04', 'slta' => 'SMAN 6 BANDUNG', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40162', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2004-09-02', 'slta' => 'SMAN 9 KODYA BANDUNG', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40175', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2005-04-25', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40526', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1C', 'angkatan' => 2024, 'tgl_lahir' => '2006-08-01', 'slta' => 'SMAN 1 CIPARAY', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40382', 'jk' => 'L', 'agama' => 'Islam'],

            // Angkatan 2025 - Kelas 1A
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2007-08-12', 'slta' => 'SMAN 1 MAJALAYA', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40376', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2007-06-04', 'slta' => 'SMA ALFA CENTAURI', 'jalur' => 'SMB / SMBM', 'wilayah' => '40522', 'kode_pos' => '', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2006-12-15', 'slta' => 'SMA LAIN-LAIN', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bogor', 'kode_pos' => '16912', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2007-06-30', 'slta' => 'SMAN 2 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40526', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2006-08-30', 'slta' => 'SMAN 6 BANDUNG', 'jalur' => 'SNBP', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40213', 'jk' => 'P', 'agama' => 'Kristen'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2006-08-19', 'slta' => 'SMAN 113 JAKARTA', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bekasi', 'kode_pos' => '17431', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2006-10-07', 'slta' => 'SMAN 4 CIMAHI', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40532', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2007-10-06', 'slta' => 'SMK LAIN-LAIN', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40377', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2007-07-20', 'slta' => 'SMA HAYATAN THAYYIBAH', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Sukabumi', 'kode_pos' => '43161', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2008-02-22', 'slta' => 'SMAN 9 Garut', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Garut', 'kode_pos' => '44188', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2006-11-06', 'slta' => 'SMAN 1 KUNINGAN', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40522', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2007-06-17', 'slta' => 'SMAN 6 BANDUNG', 'jalur' => 'SNBP', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40174', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2007-09-11', 'slta' => 'SMAN 1 RANCAEKEK', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40381', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2006-12-11', 'slta' => 'MA Sumur Bandung Cililin', 'jalur' => 'SNBT', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40526', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2007-04-12', 'slta' => 'SMAN 3 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40113', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2005-06-22', 'slta' => 'SMA LAIN-LAIN', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Jambi', 'kode_pos' => '36131', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2006-04-14', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40561', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2007-11-23', 'slta' => 'SMAN 18 Garut', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Garut', 'kode_pos' => '44183', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2006-09-25', 'slta' => 'SMKN 4 BANDUNG', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40234', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2007-10-11', 'slta' => 'SMAN 2 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40526', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2007-04-21', 'slta' => 'SMAN 6 BEKASI', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bogor', 'kode_pos' => '16969', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2007-02-09', 'slta' => '', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40295', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2007-04-29', 'slta' => 'SMAN 1 CIBADAK', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40514', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2007-03-07', 'slta' => 'SMAN 1 BALEENDAH', 'jalur' => 'SNBP', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40375', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2007-04-26', 'slta' => 'SMK Mahardika Batujajar', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40553', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2006-09-26', 'slta' => 'SMA LAIN-LAIN', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40175', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2006-04-04', 'slta' => 'MA LAIN-LAIN', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40391', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2007-01-22', 'slta' => 'SMAN 6 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => '40534', 'kode_pos' => '', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2007-01-20', 'slta' => 'SMKN 2 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40162', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2005-11-11', 'slta' => 'SMAN 1 TARAJU', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Tasikmalaya', 'kode_pos' => '46474', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1A', 'angkatan' => 2025, 'tgl_lahir' => '2007-07-13', 'slta' => '', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40552', 'jk' => 'L', 'agama' => 'Islam'],

            // Angkatan 2025 - Kelas 1B
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2007-02-24', 'slta' => 'SMA ALFA CENTAURI', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40224', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2007-07-17', 'slta' => 'SMAN 5 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40523', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2006-11-19', 'slta' => 'SMAN 18 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40233', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2007-03-23', 'slta' => 'MA PERSIS KATAPANG', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40921', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2006-12-17', 'slta' => 'MAN I GARUT', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Garut', 'kode_pos' => '44182', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2007-11-18', 'slta' => 'SMAN 1 CIKARANG UTARA', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bekasi', 'kode_pos' => '17637', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2006-11-28', 'slta' => 'SMA LAIN-LAIN', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Karawang', 'kode_pos' => '41361', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2007-02-21', 'slta' => 'SMAN 6 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40535', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2007-06-17', 'slta' => 'SMK LAIN-LAIN', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40375', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2006-12-18', 'slta' => 'SMA ANGKASA MARGAHAYU', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40226', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2006-05-10', 'slta' => 'SMAN 10 BANDUNG', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40124', 'jk' => 'L', 'agama' => 'Katolik'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2006-08-09', 'slta' => 'SMAN 5 CIMAHI', 'jalur' => 'SNBP', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40553', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2006-08-13', 'slta' => 'SMAN 1 MARGAHAYU', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40228', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2007-06-25', 'slta' => 'SMAN 14 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40194', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2006-12-29', 'slta' => 'SMA AL MUTTAQIN FULLDAY SCHOOL TASIKMALAYA', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Tasikmalaya', 'kode_pos' => '46114', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2006-07-15', 'slta' => 'SMA MEDINA', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40241', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2005-08-25', 'slta' => 'MAS PERSIS', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40287', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2006-09-05', 'slta' => 'SMAN 1 MARGAHAYU', 'jalur' => 'SNBP', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40914', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2006-09-29', 'slta' => 'SMK TARUNA BANGSA', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Bekasi', 'kode_pos' => '17612', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2007-03-15', 'slta' => 'SMAN 4 BANDUNG', 'jalur' => 'SNBP', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40222', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2007-06-11', 'slta' => 'SMAN 4 CIMAHI', 'jalur' => 'SNBT', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40526', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2007-03-14', 'slta' => 'MA LAIN-LAIN', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Bekasi', 'kode_pos' => '17168', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2007-06-20', 'slta' => 'SMKN 4 BANDUNG', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40286', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2006-05-05', 'slta' => 'SMAN 2 CIMAHI', 'jalur' => 'SNBT', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40521', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2006-05-28', 'slta' => 'SMKN 1 KATAPANG', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40921', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => null, 'slta' => 'SMAN 1 MARGAHAYU', 'jalur' => 'SNBP', 'wilayah' => 'Kabupaten Bandung', 'kode_pos' => '40226', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2007-03-30', 'slta' => 'SMKN 11 BANDUNG', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40222', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2005-12-03', 'slta' => 'SMAN 21 BANDUNG', 'jalur' => 'SNBT', 'wilayah' => 'Kota Bandung', 'kode_pos' => '40295', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2006-10-02', 'slta' => 'SMAN 1 Padalarang', 'jalur' => 'SMB / SMBM', 'wilayah' => 'Kabupaten Bandung Barat', 'kode_pos' => '40553', 'jk' => 'L', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2005-04-15', 'slta' => 'SMKN 1 CIMAHI', 'jalur' => 'SNBT', 'wilayah' => 'Kota Cimahi', 'kode_pos' => '40535', 'jk' => 'P', 'agama' => 'Islam'],
            ['kelas' => '1B', 'angkatan' => 2025, 'tgl_lahir' => '2005-08-15', 'slta' => 'SMKN 1 CIANJUR', 'jalur' => 'SNBT', 'wilayah' => 'Kabupaten Cianjur', 'kode_pos' => '43252', 'jk' => 'L', 'agama' => 'Islam'],
        ];        // Insert data mahasiswa
        $totalInserted = 0;
        foreach ($mahasiswaData as $data) {
            // Map nama ke ID
            $sltaId = isset($sltaMap[$data['slta']]) ? $sltaMap[$data['slta']] : null;
            $jalurDaftarId = isset($jalurDaftarMap[$data['jalur']]) ? $jalurDaftarMap[$data['jalur']] : null;
            $wilayahId = isset($wilayahMap[$data['wilayah']]) ? $wilayahMap[$data['wilayah']] : null;

            DB::table('mahasiswa')->insert([
                'import_id' => 1,
                'user_id_importer' => 1,
                'user_id_approver' => 1,
                'kelas' => $data['kelas'],
                'angkatan' => $data['angkatan'],
                'tgl_lahir' => $data['tgl_lahir'],
                'jenis_kelamin' => $data['jk'],
                'agama' => $data['agama'],
                'kode_pos' => $data['kode_pos'],
                'slta_id' => $sltaId,
                'jalur_daftar_id' => $jalurDaftarId,
                'wilayah_id' => $wilayahId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $totalInserted++;
        }

        $this->command->info("MahasiswaSeeder berhasil memasukkan {$totalInserted} data mahasiswa.");
    }
}
