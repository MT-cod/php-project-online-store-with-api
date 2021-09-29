<?php

namespace Database\Seeders;

use App\Models\AdditionalChar;
use App\Models\Category;
use App\Models\Goods;
use Illuminate\Database\Seeder;

class TestDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        Category::create(['parent_id' => 0, 'name' => 'Тестовая категория', 'description' => 'test', 'level' => 1]);
        Category::create(['parent_id' => 1, 'name' => 'Тестовая категория 2', 'description' => 'test', 'level' => 2]);

        AdditionalChar::create([
            'name' => 'Тестовая характеристика',
            'value' => 'test_char_value'
        ]);

        Goods::create([
            'name' => 'Тестовый товар',
            'description' => 'Тестовое описание',
            'slug' => 'test',
            'price' => 111.11,
            'category_id' => 1
        ]);

        Goods::find(1)->additionalChars()->attach(AdditionalChar::find(1));
    }
}
