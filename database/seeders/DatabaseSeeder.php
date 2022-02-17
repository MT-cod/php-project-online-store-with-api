<?php

namespace Database\Seeders;

use App\Models\AdditionalChar;
use App\Models\Category;
use App\Models\Goods;
use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     * @throws Exception
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
                    $parent_id = random_int($categAmountForLvl * ($lvl - 2) + 1, $categAmountForLvl * ($lvl - 1));
                    Category::factory()->create(['parent_id' => $parent_id, 'level' => $lvl]);
                }
            }
        }

        //Создание фейковых товаров с доп. характеристиками
        AdditionalChar::factory(30)->create();
        Goods::factory(300)->create();
        //Генерируем случайные связи между товарами и доп. характеристиками
        for ($i = 1; $i < 200; $i++) {
            Goods::find(random_int(1, Goods::count()))
                ->additionalChars()
                ->attach(AdditionalChar::find(random_int(1, AdditionalChar::count())));
        }
        //Добавим изображения товаров, очистив старое хранилище
        Storage::deleteDirectory('public');
        foreach (Goods::all() as $item) {
            $item->clearMediaCollection('images')
                ->addMediaFromUrl('http://placeimg.com/300/200/tech')
                /*->addMediaFromUrl('https://picsum.photos/300/200.jpg')*/
                ->toMediaCollection('images');
        }

        //Сгенерим случайные заказы с товарами
        Order::factory(50)->create();
        foreach (Order::all() as $order) {
            for ($i = 1; $i < random_int(5, 15); $i++) {
                $item = Goods::find(random_int(1, Goods::count()));
                $order->goods()->attach($item->id, ['price' => $item->price, 'quantity' => random_int(1, 1000)]);
            }
        }
    }
}
