<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KlasterSeeder::class, // <-- NEW KLASTER SEEDER
            DosenSeeder::class,
            RuanganSeeder::class,
            MataKuliahSeeder::class,
            JadwalSeeder::class,
            DataSeeder::class,
        ]);
    }
}
