<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Klaster;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * 5 Dosen — masing-masing terhubung ke klaster keahlian (min. 1 klaster).
 * Dosen pertama dihubungkan ke akun login `dosen@kampus.ac.id`.
 */
class DosenSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $list = [
            ['nidn' => '0012048701', 'nama' => 'Dr. Ahmad Wijaya, S.Kom., M.T.', 'email' => 'dosen@kampus.ac.id', 'klaster' => 'Pemrograman'],
            ['nidn' => '0025075602', 'nama' => 'Rina Kartika, S.Kom., M.Kom.', 'email' => 'rina.kartika@kampus.ac.id', 'klaster' => 'Basis Data'],
            ['nidn' => '0031094303', 'nama' => 'Budi Santoso, S.T., M.T.', 'email' => 'budi.santoso@kampus.ac.id', 'klaster' => 'Jaringan'],
            ['nidn' => '0047170104', 'nama' => 'Siti Nurhaliza, S.Kom., M.Si.', 'email' => 'siti.nurhaliza@kampus.ac.id', 'klaster' => 'Pemrograman'],
            ['nidn' => '0058182105', 'nama' => 'Dr. Dedi Kurniawan, S.Kom., M.M.', 'email' => 'dedi.kurniawan@kampus.ac.id', 'klaster' => 'Jaringan'],
        ];

        foreach ($list as $data) {
            $user = User::query()->where('email', $data['email'])->first();

            $dosen = Dosen::query()->updateOrCreate(
                ['nidn' => $data['nidn']],
                [
                    'user_id' => $user?->id,
                    'nama' => $data['nama'],
                    'email' => $data['email'],
                    'jabatan_fungsional' => 'Lektor',
                    'is_active' => true,
                ],
            );

            $klaster = Klaster::query()->where('nama', $data['klaster'])->first();
            if ($klaster && ! $dosen->klasters()->whereKey($klaster->id)->exists()) {
                $dosen->klasters()->attach($klaster);
            }
        }
    }
}
