<?php

namespace Database\Seeders;

use App\Models\Data;
use App\Models\User;
use Illuminate\Database\Seeder;

class DataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        if ($admin) {
            Data::factory()->count(15)->create([
                'created_by' => $admin->id,
            ]);
        }
    }
}
