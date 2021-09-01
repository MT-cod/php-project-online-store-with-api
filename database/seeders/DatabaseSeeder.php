<?php

namespace Database\Seeders;

use App\Models\AdditionalChar;
use App\Models\Category;
use App\Models\Goods;
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
        // \App\Models\User::factory(10)->create();
        Category::factory(5)->create();
        Goods::factory(5)->create();
        AdditionalChar::factory(5)->create();

        /*$this->call(CategoriesTableSeeder::class);
        $this->command->info('Categories is done');

        $this->call(GoodsTableSeeder::class);
        $this->command->info('Goods is done');

        $this->call(AdditionalCharsTableSeeder::class);
        $this->command->info('AdditionalChars is done');*/
    }
}
