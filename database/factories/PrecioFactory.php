<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Precio>
 */
class PrecioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'producto' => $this->faker->word(),
            'precio' => $this->faker->randomFloat(2, 1, 1000),
        ];
    }
}
