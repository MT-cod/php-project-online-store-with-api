<?php

namespace Database\Factories;

use App\Models\AdditionalChar;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdditionalCharFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdditionalChar::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Char_' . $this->faker->unique()->name(),
            'value' => $this->faker->text()
        ];
    }
}
