<?php

namespace Database\Seeders;

use App\Models\AdditionalChar;
use App\Models\Category;
use App\Models\Goods;
use App\Models\Movement;
use App\Models\Order;
use App\Models\User;
use App\Models\Warehouse;
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
        print_r('<html><body style="background-color: black"></body></body></html>');
        $this->outputMessage("Пользователи созданы успешно.");

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
        $this->outputMessage("Категории созданы успешно.");

        //Создание фейковых товаров с доп. характеристиками
        AdditionalChar::factory(30)->create();
        $this->outputMessage("Доп характеристики созданы успешно.");
        Goods::factory(300)->create();
        $this->outputMessage("Товары созданы успешно.");
        //Генерируем случайные связи между товарами и доп. характеристиками
        for ($i = 1; $i < 200; $i++) {
            Goods::find(random_int(1, Goods::count()))
                ->additionalChars()
                ->attach(AdditionalChar::find(random_int(1, AdditionalChar::count())));
        }
        $this->outputMessage("Связи между товарами и доп. характеристиками созданы успешно.");
        //Добавим изображения товаров, очистив старое хранилище
        Storage::deleteDirectory('public');
        foreach (Goods::all() as $item) {
            $item->clearMediaCollection('images')
                ->addMediaFromUrl('http://placeimg.com/300/200/tech')
                ->toMediaCollection('images');
            $this->outputMessage('Изображения для товара id:' . $item->id . " успешно загружено.");
        }

        //Сгенерим склады с кол-вом товаров
        Warehouse::factory(3)->create();
        foreach (Goods::all() as $item) {
            for ($i = 1; $i < 4; $i++) {
                $warehouse = Warehouse::find($i);
                $warehouse->goods()->attach($item->id, ['quantity' => random_int(1, 1000)]);
            }
        }
        $this->outputMessage("Склады созданы успешно.");

        //Сгенерим случайные заказы с товарами
        Order::factory(50)->create();
        foreach (Order::all() as $order) {
            for ($i = 1; $i < random_int(5, 15); $i++) {
                $item = Goods::find(random_int(1, Goods::count()));
                $order->goods()->attach($item->id, ['price' => $item->price, 'quantity' => random_int(1, 1000)]);
            }
        }
        $this->outputMessage("Заказы созданы успешно.");

        //Сгенерим случайные движения товаров на складах
        Movement::factory(120)->create();
        foreach (Movement::all() as $mvmnt) {
            $this->outputMessage("Генерируем движение по складам id:$mvmnt->id</div>");
            switch ($mvmnt->movement_type) {
                case 1: //пополнение склада
                    for ($i = 1; $i < random_int(5, 15); $i++) {
                        $item = Goods::find(random_int(1, Goods::count()));
                        $mvmnt->goods()
                            ->attach($item->id, ['warehouse_id' => random_int(1, 3), 'quantity' => random_int(1, 50)]);
                    }
                    break;
                case 2: //списание со склада
                    for ($i = 1; $i < random_int(5, 15); $i++) {
                        $item = Goods::find(random_int(1, Goods::count()));
                        $mvmnt->goods()
                            ->attach($item->id, ['warehouse_id' => random_int(1, 3), 'quantity' => -random_int(1, 50)]);
                    }
                    break;
                case 3: //выдача со склада по заказу
                    foreach (Order::all() as $order) {
                        if (!$order->movement_id) {
                            foreach ($order->goods()->get() as $item) {
                                $mvmnt->goods()->attach($item->id, [
                                        'warehouse_id' => random_int(1, 3),
                                        'quantity' => -$item->pivot['quantity']
                                    ]);
                            }
                            $order->movement_id = $mvmnt->id;
                            $mvmnt->order_id = $order->id;
                            $order->save();
                            $mvmnt->save();
                            break;
                        }
                    }
                    break;
                case 4: //движение между складами
                    $warehouses = [1, 2, 3];
                    shuffle($warehouses);
                    for ($i = 1; $i < random_int(5, 15); $i++) {
                        $item = Goods::find(random_int(1, Goods::count()));
                        $quantity = random_int(1, 50);
                        $mvmnt->goods()
                            ->attach($item->id, ['warehouse_id' => $warehouses[0], 'quantity' => $quantity]);
                        $mvmnt->goods()
                            ->attach($item->id, ['warehouse_id' => $warehouses[1], 'quantity' => -$quantity]);
                    }
                    break;
            }
        }
    }

    private function outputMessage(string $mess): void
    {
        print_r("<div style='background-color: black;color: lawngreen;font-size: .7rem;'>$mess</div><script>window.scrollTo(0,document.body.scrollHeight);</script>");
    }
}
