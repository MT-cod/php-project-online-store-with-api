<?php

namespace Http\Controllers\Api;

use App\Models\Goods;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ApiGoodsControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $testUserToken;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('db:seed TestDatabaseSeeder');
        $this->testUserToken = User::factory()->create()->createToken('Main token')->plainTextToken;
    }

    public function testIndex()
    {
        $response = $this->get(
            '/api/goods?filter[category_ids]=1&filter[additChar_ids]=1&sort[name]=asc&perpage=10'
        );
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Список товаров с дополнительными характеристиками успешно получен.'])
            ->assertJsonFragment(['name' => 'Test item']);

        $errorResp = $this->get('/api/goods?filter[category_ids]=[2]');
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(
                ['error' => ['filter.category_ids' => ['Поле filter.category_ids имеет ошибочный формат.']]]
            );
    }

    public function testSlug()
    {
        $response = $this->get('/api/goods/slug/test');
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Данные о товаре успешно получены.'])
            ->assertJsonFragment(['name' => 'Test item']);

        $errorResp = $this->get('/api/goods/slug/bla');
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(['error' => 'Не удалось получить данные о товаре по запрошенному slug.']);
    }

    public function testShow()
    {
        $response = $this->get('/api/goods/1');
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Данные о товаре успешно получены.'])
            ->assertJsonFragment(['name' => 'Test item']);

        $errorResp = $this->get('/api/goods/0');
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(['error' => 'Не удалось получить данные о товаре с id:0.']);
    }

    public function testStore()
    {
        $response = $this->json(
            'post',
            '/api/goods',
            ['name' => 'testItem2', 'slug' => 'test2', 'category_id' => 2, 'additChars' => [1]],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Товар testItem2 успешно создан.'])
            ->assertJsonFragment(["name" => "testItem2"]);

        $errorResp = $this->json(
            'post',
            '/api/goods',
            ['name' => 'testItem3', 'slug' => 'test3', 'category_id' => 1, 'additChars' => [1]],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(
                ['error' => ['category_id' => ['Категории 1-го уровня не могут принадлежать товары.']]]
            );
        $errorResp = $this->json(
            'post',
            '/api/goods',
            ['name' => 'testItem3', 'slug' => 'test3', 'category_id' => 2, 'additChars' => [7]],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(
                ['error' => ['additChars' => ['Указана несуществующая доп. характеристика с id:7 для нового товара.']]]
            );
    }

    public function testUpdate()
    {
        $response = $this->json(
            'post',
            '/api/goods/1',
            [
                '_method' => 'PATCH',
                'name' => 'testItem2',
                'slug' => 'test2',
                'description' => 'bla-bla',
                'price' => 333.33,
                'category_id' => 2,
                'additChars' => [1]
            ],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Параметры товара успешно изменены.'])
            ->assertJsonFragment(["name" => "testItem2"]);

        $errorResp = $this->json(
            'post',
            '/api/goods/1',
            ['_method' => 'PATCH', 'category_id' => 1, 'additChars' => [1]],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(
                ['error' => ['category_id' => ['Категории 1-го уровня не могут принадлежать товары.']]]
            );
        $errorResp = $this->json(
            'post',
            '/api/goods/1',
            ['_method' => 'PATCH', 'additChars' => [7]],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(
                ['error' => ['additChars' => ['Указана несуществующая доп. характеристика с id:7 для нового товара.']]]
            );
    }

    public function testDestroy()
    {
        $response = $this->json(
            'post',
            '/api/goods/1',
            ['_method' => 'DELETE'],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => "Товар успешно удален."]);
        $this->assertDatabaseMissing('goods', ['id' => 1]);

        Goods::create(['name' => 'testItem', 'slug' => 'test', 'category_id' => 2]);
        Order::create([
            'name' => 'name',
            'email' => 'email@mail.ru',
            'phone' => '1234567896',
            'user_id' => 1
        ])->goods()->attach(2, ['price' => 111, 'quantity' => 10]);
        $errorResp = $this->json(
            'post',
            '/api/goods/2',
            ['_method' => 'DELETE'],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(
                ['error' => 'Не удалось удалить товар с id:2. Товар может участвовать в транзакциях.']
            );
    }
}
