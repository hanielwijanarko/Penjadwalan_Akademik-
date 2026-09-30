<?php

namespace Database\Factories;

use App\Models\Data;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Data>
 */
class DataFactory extends Factory
{
    protected $model = Data::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'created_by' => User::factory()->admin(),
        ];
    }
}
