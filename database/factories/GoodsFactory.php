<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Goods;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Throwable;

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
     * @throws Throwable
     */
    public function definition()
    {
        $name = $this->faker->words(3, true);
        return [
            'name' => 'Товар ' . $name,
            'description' => $this->faker->words(10, true),
            'slug' => Str::slug($name),
            'price' => random_int(10000, 20000) / 100,
            'category_id' => random_int(
                (Category::all()
                    ->where('level', 1)
                    ->count()) + 1,
                Category::count()
            )
        ];
    }
}
