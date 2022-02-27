<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Throwable;

class MovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     * @throws Throwable
     */
    public function definition()
    {
        return ['description' => $this->faker->words(10, true), 'movement_type' => random_int(1, 4)];
    }
}
