<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Costo>
 */
class CostoFactory extends Factory
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
            'cantidad' => $this->faker->numberBetween(1, 100),
            'precio' => $this->faker->randomFloat(2, 1, 1000),
            'total' => function (array $attributes) {
                return $attributes['cantidad'] * $attributes['precio'];
            },
        ];
    }
}
