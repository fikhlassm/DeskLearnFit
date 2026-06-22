<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama_kelas' => 'Matematika Dasar A',
                'mata_pelajaran' => 'Matematika',
                'kode_kelas' => 'MTK-A-01',
                'deskripsi' => 'Kelas matematika dasar untuk semester 1',
                'kapasitas' => 30,
                'status' => 'aktif',
            ],
            [
                'nama_kelas' => 'Fisika Lanjutan B',
                'mata_pelajaran' => 'Fisika',
                'kode_kelas' => 'FIS-B-02',
                'deskripsi' => 'Kelas fisika untuk siswa tingkat lanjut',
                'kapasitas' => 25,
                'status' => 'aktif',
            ],
            [
                'nama_kelas' => 'Bahasa Inggris C',
                'mata_pelajaran' => 'Bahasa Inggris',
                'kode_kelas' => 'BIG-C-03',
                'deskripsi' => 'Kelas komunikasi bahasa Inggris',
                'kapasitas' => 20,
                'status' => 'draf',
            ],
            [
                'nama_kelas' => 'Pemrograman Web D',
                'mata_pelajaran' => 'Teknologi Informasi',
                'kode_kelas' => 'TI-D-04',
                'deskripsi' => 'Kelas pemrograman web dengan Laravel',
                'kapasitas' => 28,
                'status' => 'selesai',
            ],
        ];

        foreach ($data as $item) {
            Kelas::updateOrInsert(
                ['kode_kelas' => $item['kode_kelas']], // cek berdasarkan ini
                $item
            );
        }
    }
}
