<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Goods;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GoodsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Goods::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->unique()->words(2, true);
        return [
            'name' => 'Thing ' . $name,
            'description' => $this->faker->text(),
            'slug' => Str::slug($name),
            'price' => rand(1000, 2000),
            'category_id' => rand(1, Category::count())
        ];
    }
}
