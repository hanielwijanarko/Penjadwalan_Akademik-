<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = [
            'name' => 'Admin Sistem',
            'email' => 'admin@kampus.ac.id',
            'role' => User::ROLE_ADMIN,
        ];

        User::query()->firstOrCreate(
            ['email' => $admin['email']],
            array_merge($admin, [
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ])
        );

        $user = [
            'name' => 'Pengguna Dosen',
            'email' => 'dosen@kampus.ac.id',
            'role' => User::ROLE_USER,
        ];

        User::query()->firstOrCreate(
            ['email' => $user['email']],
            array_merge($user, [
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ])
        );
    }
}
