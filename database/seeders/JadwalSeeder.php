<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\MataKuliah;
use App\Models\Ruangan;
use App\Models\Semester;
use App\Services\JadwalService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Contoh jadwal pada semester aktif.
 *
 * Jam selesai dihitung otomatis dari SKS memakai JabatanService agar data
 * seed konsisten dengan aturan bisnis "1 SKS = 50 menit".
 */
class JadwalSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * @var array<int, array{hari: string, jam_mulai: string, matkul: string, ruangan: string, kelas: string}>
     */
    private array $jadwal = [
        ['hari' => 'senin', 'jam_mulai' => '07:30', 'matkul' => 'IF101', 'ruangan' => 'R-101', 'kelas' => 'A'],
        ['hari' => 'senin', 'jam_mulai' => '13:00', 'matkul' => 'IF303', 'ruangan' => 'LAB-BDB', 'kelas' => 'A'],
        ['hari' => 'selasa', 'jam_mulai' => '08:00', 'matkul' => 'IF404', 'ruangan' => 'LAB-JAR', 'kelas' => 'A'],
        ['hari' => 'rabu', 'jam_mulai' => '09:00', 'matkul' => 'IF202', 'ruangan' => 'R-201', 'kelas' => 'B'],
        ['hari' => 'kamis', 'jam_mulai' => '14:00', 'matkul' => 'IF505', 'ruangan' => 'R-102', 'kelas' => 'A'],
    ];

    public function __construct(private readonly JadwalService $jadwalService = new JadwalService) {}

    public function run(): void
    {
        $semester = Semester::query()->where('is_active', true)->first();

        if (! $semester) {
            return;
        }

        foreach ($this->jadwal as $baris) {
            $mataKuliah = MataKuliah::query()->where('kode', $baris['matkul'])->first();
            $ruangan = Ruangan::query()->where('kode', $baris['ruangan'])->first();

            if (! $mataKuliah || ! $ruangan) {
                continue;
            }

            // Klasterisasi: dosen diambil dari klaster matkul yang sama.
            $dosen = $this->jadwalService->dosenPengampu($mataKuliah, $baris['hari'], $baris['jam_mulai'], $mataKuliah->sks, $semester->id)->first()
                ?? $mataKuliah->klaster->anggotaDosen()->first();

            if (! $dosen) {
                continue;
            }

            Jadwal::query()->updateOrCreate(
                [
                    'semester_id' => $semester->id,
                    'mata_kuliah_id' => $mataKuliah->id,
                    'kelas' => $baris['kelas'],
                ],
                [
                    'dosen_id' => $dosen->id,
                    'ruangan_id' => $ruangan->id,
                    'hari' => $baris['hari'],
                    'jam_mulai' => $baris['jam_mulai'],
                    'jam_selesai' => $this->jadwalService->hitungJamSelesai($baris['jam_mulai'], $mataKuliah->sks),
                    'jumlah_mahasiswa' => min($ruangan->kapasitas, 30),
                ],
            );
        }
    }
}
