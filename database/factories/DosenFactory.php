<?php

namespace Database\Factories;

use App\Models\Dosen;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dosen>
 */
class DosenFactory extends Factory
{
    protected $model = Dosen::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'nidn' => fake()->unique()->numberBetween(1000000000, 1999999999),
            'nama' => fake()->name('male'),
            'email' => fake()->unique()->safeEmail(),
            'no_hp' => fake()->numerify('0812########'),
            'jabatan_fungsional' => fake()->randomElement(['Lektor Kepala', 'Asisten Ahli', 'Lektor', 'Guru Besar']),
            'is_active' => true,
        ];
    }
}
