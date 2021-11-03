<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::findOrFail(rand(1, User::count()));
        return [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'user_id' => $user->id,
            'address' => $this->faker->text(50),
            'comment' => $this->faker->text(50),
            'completed' => rand(0, 1)
        ];
    }
}
