<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Throwable;

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
     * @throws Throwable
     */
    public function definition()
    {
        $user = User::findOrFail(random_int(1, User::count()));
        return [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'user_id' => $user->id,
            'address' => $this->faker->address,
            'comment' => $this->faker->realText(50),
            'completed' => random_int(0, 1)
        ];
    }
}
