<?php

namespace Database\Seeders;

use App\Models\Goods;
use Illuminate\Database\Seeder;

class GoodsTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Goods::create([
            'name' => 'Тестовый товар',
            'description' => 'Тестовое описание',
            'slug' => 'test',
            'price' => 111.11
        ]);
    }
}
