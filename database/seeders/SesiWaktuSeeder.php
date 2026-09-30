<?php

namespace Database\Seeders;

use App\Models\SesiWaktu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * 3 Klaster Sesi Waktu: Pagi, Siang, Sore.
 * (rentang membentuk aturan jam_kuliah: pagi 07.00-11.59, siang 12.00-15.59, sore 16.00-19.59)
 */
class SesiWaktuSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $sesi = [
            ['kode' => 'PAGI', 'nama' => 'Pagi', 'jam_mulai' => '07:00:00', 'jam_selesai' => '11:59:59', 'urutan' => 1],
            ['kode' => 'SIANG', 'nama' => 'Siang', 'jam_mulai' => '12:00:00', 'jam_selesai' => '15:59:59', 'urutan' => 2],
            ['kode' => 'SORE', 'nama' => 'Sore', 'jam_mulai' => '16:00:00', 'jam_selesai' => '19:59:59', 'urutan' => 3],
        ];

        foreach ($sesi as $data) {
            SesiWaktu::query()->updateOrCreate(['kode' => $data['kode']], $data + ['is_active' => true]);
        }
    }
}
