<?php

namespace Http\Controllers\Api;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ApiOrdersControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $testUserToken;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('db:seed TestDatabaseSeeder');
        $user = User::factory()->create();
        $this->testUserToken = $user->createToken('Main token')->plainTextToken;
        Order::create([
            'name' => 'testName',
            'email' => 'testEmail@test.test',
            'phone' => '+1234567896',
            'user_id' => 1
        ])->goods()->attach(1, ['price' => 111, 'quantity' => 10]);
    }

    public function testIndex()
    {
        $response = $this->get(
            '/api/orders?filter[name]=test&sort[name]=asc&perpage=10',
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Список заказов успешно получен.'])
            ->assertJsonFragment(['name' => 'testName']);

        $errorResp = $this->get(
            '/api/orders?filter[level]=bla',
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(['errors' => ['filter.level' => ['Фильтрация данных по полю filter.level некорректна.']]]);
    }

    public function testOwnOrders()
    {
        $response = $this->get('/api/orders/own_orders', ['Authorization' => 'Bearer ' . $this->testUserToken]);
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Список заказов пользователя успешно получен.'])
            ->assertJsonFragment(['phone' => '+1234567896']);

        $errorResp = $this->get(
            '/api/orders/own_orders?filter[level]=bla',
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(['errors' => ['filter.level' => ['Фильтрация данных по полю filter.level некорректна.']]]);
    }

    public function testShow()
    {
        $response = $this->get('/api/orders/1', ['Authorization' => 'Bearer ' . $this->testUserToken]);
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Заказ успешно получен.'])
            ->assertJsonFragment(['completed' => 0]);

        $errorResp = $this->get(
            '/api/orders/111',
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(['errors' => 'Не удалось найти заказ id:111.']);
    }

    public function testStore()
    {
        User::find(1)->goodsInBasket()->attach(1, ['quantity' => 10]);
        $response = $this->json(
            'post',
            '/api/orders',
            ['name' => 'testName2', 'email' => 'testEmail2@test.test', 'phone' => '+12345678967'],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Заказ успешно создан.'])
            ->assertJsonFragment(["name" => "testName2"]);

        $errorResp = $this->json(
            'post',
            '/api/orders',
            ['name' => 'testName2', 'email' => 'testEmail2@test.test', 'phone' => '+12345678967'],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(
                ['errors' => 'Ошибка создания заказа. Корзина пользователя пуста.']
            );
    }

    public function testUpdate()
    {
        $response = $this->json(
            'post',
            '/api/orders/1',
            ['_method' => 'PATCH', 'completed' => 1],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Заказ успешно обновлен.'])
            ->assertJsonFragment(['completed' => 1]);

        $errorResp = $this->json(
            'post',
            '/api/goods/111',
            ['_method' => 'PATCH'],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(
                ['errors' => ['id' => ['Выбранное значение для id некорректно.']]]
            );
    }
}
