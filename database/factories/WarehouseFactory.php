<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Throwable;

class WarehouseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     * @throws Throwable
     */
    public function definition()
    {
        return [
            'name' => 'Склад_' . $this->faker->word(),
            'description' => $this->faker->realText(100),
            'address' => $this->faker->address,
            'priority' => random_int(1, 100)
        ];
    }
}
