<?php

namespace Database\Factories;

use App\Models\Ruangan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ruangan>
 */
class RuanganFactory extends Factory
{
    protected $model = Ruangan::class;

    public function definition(): array
    {
        return [
            'kode' => strtoupper(fake()->unique()->bothify('R-###??')),
            'nama' => 'Kelas '.fake()->unique()->bothify('??##'),
            'kapasitas' => fake()->numberBetween(20, 40),
            'gedung' => 'Gedung '.fake()->randomElement(['A', 'B', 'C', 'D']),
            'lantai' => (string) fake()->numberBetween(1, 4),
            'fasilitas' => fake()->randomElements(['Proyektor', 'AC', 'Whiteboard', 'PC', 'Speaker'], fake()->numberBetween(1, 3)),
            'is_active' => true,
        ];
    }
}
