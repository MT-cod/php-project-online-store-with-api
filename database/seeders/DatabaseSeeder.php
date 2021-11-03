<?php

namespace Database\Seeders;

use App\Models\AdditionalChar;
use App\Models\Category;
use App\Models\Goods;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(20)->create();

        //Создание фейковых категорий
        $maxCategLevel = 3;
        $categAmountForLvl = 20;
        Category::factory($categAmountForLvl)->create(['level' => 1]);
        if ($maxCategLevel > 1) {
            for ($lvl = 2; $lvl <= $maxCategLevel; $lvl++) {
                for ($i = 1; $i <= $categAmountForLvl; $i++) {
                    $parent_id = rand($categAmountForLvl * ($lvl - 2) + 1, $categAmountForLvl * ($lvl - 1));
                    Category::factory()->create(['parent_id' => $parent_id, 'level' => $lvl]);
                }
            }
        }

        //Создание фейковых товаров с доп. характеристиками
        AdditionalChar::factory(15)->create();
        Goods::factory(300)->create();
        //Генерируем случайные связи между товарами и доп. характеристиками
        for ($i = 1; $i < 200; $i++) {
            Goods::find(rand(1, Goods::count()))->additionalChars()->attach(AdditionalChar::find(rand(1, AdditionalChar::count())));
        }

        //Сгенерим случайные заказы с товарами
        Order::factory(50)->create();
        foreach (Order::all() as $order) {
            for ($i = 1; $i < rand(5, 15); $i++) {
                $item = Goods::find(rand(1, Goods::count()));
                $order->goods()->attach($item->id, ['price' => $item->price, 'quantity' => rand(1, 1000)]);
            }
        }
    }
}
