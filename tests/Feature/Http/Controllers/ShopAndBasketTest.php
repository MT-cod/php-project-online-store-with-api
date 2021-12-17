<?php

namespace Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ShopAndBasketTest extends TestCase
{
    use RefreshDatabase;

    private Collection|Model $testUser;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('db:seed TestDatabaseSeeder');
        $this->testUser = User::factory()->create();
    }

    public function testShopIndex(): void
    {
        $response = $this->get('/');
        $response->assertSee('Test item');
    }

    public function testBasketIndex(): void
    {
        $this->storeTestBasket();
        $response = $this->get('/basket');
        $this->assertSame('Test item', $response['basket'][1]['name']);
    }

    public function testStore(): void
    {
        $this->storeTestBasket();
        $this->assertSame(1, session('basket')[1]['id']);
        $this->assertSame(333, session('basket')[1]['quantity']);
        Auth::loginUsingId(1);
        $this->storeTestBasket();
        $this->assertDatabaseHas('baskets', ["goods_id" => "1", "user_id" => "1", "quantity" => "333"]);
    }

    public function testUpdate(): void
    {
        $this->storeTestBasket();
        $this->post(route('basket.update', 1), [
            '_method' => 'PATCH',
            'basket' => [1 => 444]
        ]);
        $this->assertSame(444, session('basket')[1]['quantity']);
        Auth::loginUsingId(1);
        $this->storeTestBasket();
        $this->post(route('basket.update', 1), [
            '_method' => 'PATCH',
            'basket' => [1 => 444]
        ]);
        $this->assertDatabaseHas('baskets', ["goods_id" => "1", "user_id" => "1", "quantity" => "444"]);
    }

    public function testDestroy(): void
    {
        $this->storeTestBasket();
        $this->post(route('basket.destroy', 0), ['_method' => 'DELETE']);
        $this->assertFalse(session()->has('basket'));

        $this->storeTestBasket();
        $this->post(route('basket.destroy', 1), ['_method' => 'DELETE']);
        $this->assertFalse(session()->has('basket.1'));

        Auth::loginUsingId(1);
        $this->storeTestBasket();
        $this->post(route('basket.destroy', 0), ['_method' => 'DELETE']);
        $this->assertDatabaseMissing('baskets', ["goods_id" => "1", "user_id" => "1", "quantity" => "333"]);

        $this->storeTestBasket();
        $this->post(route('basket.destroy', 1), ['_method' => 'DELETE']);
        $this->assertDatabaseMissing('baskets', ["goods_id" => "1", "user_id" => "1", "quantity" => "333"]);
    }

    private function storeTestBasket()
    {
        return $this->post(route('basket.store'), [
            'id' => 1,
            'quantity' => 333
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
