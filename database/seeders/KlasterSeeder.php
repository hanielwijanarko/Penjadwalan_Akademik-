<?php

namespace Database\Seeders;

use App\Models\Klaster;
use Illuminate\Database\Seeder;

class KlasterSeeder extends Seeder
{
    public function run(): void
    {
        $klasters = [
            [
                'kode' => 'PRG',
                'nama' => 'Pemrograman',
                'deskripsi' => 'Pengembangan software, arsitektur, algoritma, dan bahasa pemrograman.',
                'is_active' => true,
            ],
            [
                'kode' => 'BD',
                'nama' => 'Basis Data',
                'deskripsi' => 'Data layer, arsitektur data, normalisasi, dan sains data.',
                'is_active' => true,
            ],
            [
                'kode' => 'JRG',
                'nama' => 'Jaringan',
                'deskripsi' => 'Infrastruktur TI, cloud computing, keamanan siber, dan jaringan komputer.',
                'is_active' => true,
            ],
            [
                'kode' => 'SI',
                'nama' => 'Sistem Informasi',
                'deskripsi' => 'Manajemen SI, bisnis digital, e-commerce, dan tata kelola TI.',
                'is_active' => true,
            ],
        ];

        foreach ($klasters as $klaster) {
            Klaster::updateOrCreate(['kode' => $klaster['kode']], $klaster);
        }
    }
}
