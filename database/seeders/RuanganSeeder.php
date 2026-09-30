<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use App\Models\SesiWaktu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * 5 Ruangan — terhubung ke klaster sesi waktu (Pagi / Siang / Sore / Semua).
 */
class RuanganSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $diaudit = [
            [
                'kode' => 'R-101', 'nama' => 'Kelas A-101', 'kapasitas' => 40, 'gedung' => 'Gedung A', 'lantai' => '1',
                'fasilitas' => ['Proyektor', 'AC', 'Whiteboard'],
                'sesi' => ['PAGI', 'SIANG'],
            ],
            [
                'kode' => 'R-102', 'nama' => 'Kelas A-102', 'kapasitas' => 40, 'gedung' => 'Gedung A', 'lantai' => '1',
                'fasilitas' => ['Proyektor', 'AC', 'Whiteboard'],
                'sesi' => ['SIANG', 'SORE'],
            ],
            [
                'kode' => 'R-201', 'nama' => 'Kelas B-201', 'kapasitas' => 35, 'gedung' => 'Gedung B', 'lantai' => '2',
                'fasilitas' => ['Proyektor', 'AC', 'Whiteboard'],
                'sesi' => ['PAGI', 'SIANG', 'SORE'],
            ],
            [
                'kode' => 'LAB-JAR', 'nama' => 'Lab Jaringan', 'kapasitas' => 24, 'gedung' => 'Gedung C', 'lantai' => '1',
                'fasilitas' => ['Lab Jaringan', 'Rack Server', 'AC'],
                'sesi' => ['PAGI', 'SIANG'],
            ],
            [
                'kode' => 'LAB-BDB', 'nama' => 'Lab Basis Data', 'kapasitas' => 24, 'gedung' => 'Gedung C', 'lantai' => '2',
                'fasilitas' => ['PC', 'Server DB', 'AC'],
                'sesi' => ['SIANG', 'SORE'],
            ],
        ];

        foreach ($diaudit as $data) {
            $ruangan = Ruangan::query()->updateOrCreate(
                ['kode' => $data['kode']],
                [
                    'nama' => $data['nama'],
                    'kapasitas' => $data['kapasitas'],
                    'gedung' => $data['gedung'],
                    'lantai' => $data['lantai'],
                    'fasilitas' => $data['fasilitas'],
                    'is_active' => true,
                ],
            );

            foreach ($data['sesi'] as $kodeSesi) {
                $sesi = SesiWaktu::query()->where('kode', $kodeSesi)->first();
                if ($sesi && ! $ruangan->sesiWaktus()->whereKey($sesi->id)->exists()) {
                    $ruangan->sesiWaktus()->attach($sesi);
                }
            }
        }
    }
}
