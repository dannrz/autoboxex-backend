<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Insumo>
 */
class InsumoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'refaccion' => $this->faker->word(),
            'tipo' => $this->faker->word(),
            'cantidad' => $this->faker->numberBetween(1, 100),
            'precio' => $this->faker->randomFloat(2, 1, 1000),
            'importe' => $this->faker->randomFloat(2, 1, 10000),
        ];
    }
}
