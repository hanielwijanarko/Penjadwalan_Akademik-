<?php

namespace Database\Seeders;

use App\Models\Klaster;
use App\Models\MataKuliah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * 5 Mata Kuliah — terhubung ke klaster keahlian, masing-masing memiliki SKS
 * (aturan: durasi = SKS × 50 menit).
 */
class MataKuliahSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $matkul = [
            ['kode' => 'IF101', 'nama' => 'Pemrograman Dasar', 'sks' => 3, 'klaster' => 'Pemrograman', 'deskripsi' => 'Konsep dasar algoritma dan pemrograman dengan Python/PHP.', 'sifat' => 'wajib'],
            ['kode' => 'IF202', 'nama' => 'Pemrograman Web Lanjut', 'sks' => 3, 'klaster' => 'Pemrograman', 'deskripsi' => 'Pengembangan aplikasi web full-stack modern.', 'sifat' => 'wajib'],
            ['kode' => 'IF303', 'nama' => 'Basis Data Lanjut', 'sks' => 3, 'klaster' => 'Basis Data', 'deskripsi' => 'Perancangan database tingkat lanjut, normalisasi & optimasi query.', 'sifat' => 'wajib'],
            ['kode' => 'IF404', 'nama' => 'Jaringan Komputer', 'sks' => 3, 'klaster' => 'Jaringan', 'deskripsi' => 'Model OSI/TCP-IP, subnetting, dan routing dasar.', 'sifat' => 'wajib'],
            ['kode' => 'IF505', 'nama' => 'Keamanan Jaringan', 'sks' => 2, 'klaster' => 'Jaringan', 'deskripsi' => 'Firewall, kriptografi, dan mitigasi ancaman jaringan.', 'sifat' => 'pilihan'],
        ];

        foreach ($matkul as $data) {
            $klaster = Klaster::query()->where('nama', $data['klaster'])->firstOrFail();

            MataKuliah::query()->updateOrCreate(
                ['kode' => $data['kode']],
                [
                    'nama' => $data['nama'],
                    'sks' => $data['sks'],
                    'deskripsi' => $data['deskripsi'],
                    'sifat' => $data['sifat'],
                    'klaster_id' => $klaster->id,
                    'is_active' => true,
                ],
            );
        }
    }
}
