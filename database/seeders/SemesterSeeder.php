<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * 1 Semester Aktif (sesuai spesifikasi).
 */
class SemesterSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Semester::query()->updateOrCreate(
            ['kode' => '2025-GANJIL'],
            [
                'nama' => 'Semester Ganjil',
                'tahun_ajaran' => '2025/2026',
                'jenis' => 'ganjil',
                'tanggal_mulai' => '2025-08-18',
                'tanggal_selesai' => '2026-01-10',
                'is_active' => true,
            ],
        );
    }
}
