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
        $name = $this->faker->words(3, true);
        return [
            'name' => 'Товар ' . $name,
            'description' => $this->faker->text(),
            'slug' => Str::slug($name),
            'price' => rand(10000, 20000) * 0.01,
            'category_id' => rand(
                (Category::all()->where('level', 1)->count()) + 1, Category::count())
        ];
    }
}
