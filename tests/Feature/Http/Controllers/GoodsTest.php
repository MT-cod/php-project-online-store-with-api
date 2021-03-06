<?php

namespace Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class GoodsTest extends TestCase
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

    public function testIndex(): void
    {
        $response = $this->get('/goods');
        $response->assertOk();
        $this->assertContains('Test item', $response['goods'][0]->toArray());
        $this->assertContains('Тестовое описание', $response['goods'][0]->toArray());
        $this->assertContains(111.11, $response['goods'][0]->toArray());
    }

    public function testShow(): void
    {
        $response = $this->get('/goods/1');
        $this->assertSame('Test item', $response['name']);
    }

    public function testCreate(): void
    {
        $response = $this->get('/goods/create');
        $response->assertOk();
        $this->assertSame(['id' => 1, 'name' => 'Тестовая категория'], $response['categories'][0]);
        $this->assertSame(['id' => 1, 'name' => 'Тестовая характеристика', 'value' => 'test_char_value'], $response['additCharacteristics'][0]);
    }

    public function testStore(): void
    {
        $response = $this->storeTestGoods();
        $response->assertStatus(400);
        Auth::loginUsingId(1);
        $this->storeTestGoods();
        $this->assertDatabaseHas('goods', ['name' => 'Test item 2']);
    }

    public function testEdit(): void
    {
        /*$response = $this->get('/goods/1/edit');
        $response->assertStatus(400);*/
        Auth::loginUsingId(1);
        $response = $this->get('/goods/1/edit');
        $this->assertSame(['id' => 1, 'name' => 'Тестовая категория'], $response['categories'][0]);
        $this->assertSame(['id' => 1, 'name' => 'Тестовая характеристика', 'value' => 'test_char_value'], $response['additCharacteristics'][0]);
    }

    public function testUpdate(): void
    {
        $response = $this->post(route('goods.update', 1), ['_method' => 'PATCH', null]);
        $response->assertStatus(400);
        Auth::loginUsingId(1);
        $this->post(route('goods.update', 1), [
            '_method' => 'PATCH',
            'name' => 'Тестовый товар2',
            'description' => 'Тестовое описание2',
            'slug' => 'test2',
            'price' => 222,
            'category_id' => 2
        ]);
        $this->assertDatabaseHas('goods', ['name' => 'Тестовый товар2']);
        $response = $this->get('/goods');
        $this->assertContains('Тестовый товар2', $response['goods'][0]->toArray());
    }

    public function testDestroy(): void
    {
        $response = $this->post(route('goods.destroy', 1), ['_method' => 'DELETE']);
        $response->assertStatus(403);
        Auth::loginUsingId(1);
        $this->post(route('goods.destroy', 1), ['_method' => 'DELETE']);
        $this->assertDatabaseMissing('goods', ['name' => 'Тестовый товар']);
    }

    private function storeTestGoods(): TestResponse
    {
        return $this->post(route('goods.store'), [
            'name' => 'Test item 2',
            'description' => 'Тестовое описание',
            'slug' => 'test2',
            'price' => 111.11,
            'category_id' => 2
        ]);
    }
}
