<?php

namespace Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class OrdersTest extends TestCase
{
    use RefreshDatabase;

    private Collection|Model $testUser;
    private Collection|Model $testOrder;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('db:seed TestDatabaseSeeder');
        $this->testUser = User::factory()->create();
        $this->testOrder = Order::factory()->create();
    }

    public function testIndex(): void
    {
        $response = $this->get('/orders');
        $response->assertOk();
        $response->assertSeeTextInOrder([$this->testOrder->name], true);
    }

    public function testCreate(): void
    {
        Auth::loginUsingId(1);
        $this->post(route('basket.store'), [
            'id' => 1,
            'quantity' => 333
        ]);
        $response = $this->get('/orders/create');
        $this->assertSame($this->testUser->name, $response['user_data']['name']);
        $this->assertSame(333, $response['basket'][1]['quantity']);
    }

    public function testStore(): void
    {
        $this->storeTestOrder();
        $this->assertDatabaseHas('orders', ['name' => $this->testOrder->name]);
        $response = $this->get('/orders');
        $response->assertSeeTextInOrder([$this->testOrder->name], true);
    }

    public function testEdit(): void
    {
        $this->storeTestOrder();
        $response = $this->get('/orders/1/edit');
        $this->assertSame($this->testOrder->name, $response['name']);
    }

    public function testUpdate(): void
    {
        $this->storeTestOrder();
        $this->post(route('orders.update', 1), ['_method' => 'PATCH','completed' => 1]);
        $this->assertDatabaseHas('orders', ['completed' => 1]);
        $response = $this->get('/orders');
        $response->assertSeeTextInOrder(['Заказ №1 завершён.'], true);
    }

    private function storeTestOrder(): void
    {
        $this->post(route('orders.store'), [
            'name' => $this->testUser->name,
            'email' => $this->testUser->email,
            'phone' => $this->testUser->phone
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
