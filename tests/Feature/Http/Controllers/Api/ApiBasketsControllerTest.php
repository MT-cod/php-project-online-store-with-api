<?php

namespace Http\Controllers\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ApiBasketsControllerTest extends TestCase
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

    public function testOwnBasket(): void
    {
        $response = $this->get('/api/baskets/own_basket', ['Authorization' => 'Bearer ' . $this->testUserToken]);
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Корзина пользователя пуста.']);

        $this->storeTestBasket();
        $response = $this->get('/api/baskets/own_basket', ['Authorization' => 'Bearer ' . $this->testUserToken]);
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Корзина пользователя успешно получена.'])
            ->assertJsonFragment(["name" => "Test item", "quantity" => 100]);
    }

    public function testStore(): void
    {
        $response = $this->json(
            'post',
            '/api/baskets',
            ['basket' => [1 => 10]],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Корзина успешно сохранена.'])
            ->assertJsonFragment(["name" => "Test item", "quantity" => 10]);

        $errorResp = $this->json(
            'post',
            '/api/baskets',
            ['basket' => 'bla'],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(['errors' => ['basket' => ['Передана некорректная структура данных.']]]);
        $errorResp = $this->json(
            'post',
            '/api/baskets',
            ['basket' => ['bla' => 10]],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(['errors' => ['basket' => ['Указан некорректный идентификатор товара.']]]);
        $errorResp = $this->json(
            'post',
            '/api/baskets',
            ['basket' => ['1' => -10]],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(['errors' => ['basket' => ['Указано некорректное количество товара.']]]);
    }

    public function testDestroy(): void
    {
        $this->storeTestBasket();
        $response = $this->json(
            'post',
            '/api/baskets/1',
            ['_method' => 'DELETE'],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => "Позиция успешно удалена."]);
        $this->assertDatabaseMissing('baskets', ['goods_id' => 1, 'user_id' => 1]);

        $errorResp = $this->json(
            'post',
            '/api/baskets/1',
            ['_method' => 'DELETE'],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(['errors' => 'Не удалось найти позицию с id:1 в корзине пользователя.']);
    }

    public function testPurge(): void
    {
        $this->storeTestBasket();
        $response = $this->json(
            'post',
            '/api/baskets/purge',
            ['_method' => 'DELETE'],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => "Корзина пользователя полностью очищена."]);
        $this->assertDatabaseMissing('baskets', ['goods_id' => 1, 'user_id' => 1]);
    }

    private function storeTestBasket(): void
    {
        $basket = [1 => ['quantity' => 100]];
        User::find(1)->goodsInBasket()->sync($basket);
    }
}
