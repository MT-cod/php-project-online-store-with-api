<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Throwable;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws Throwable
     */
    public function definition()
    {
        return [
            'name' => 'Категория_' . $this->faker->word() . random_int(1, 100000000),
            'description' => $this->faker->words(10, true)
        ];
    }
}
