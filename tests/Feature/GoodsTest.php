<?php

namespace Tests\Feature;

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
        $response->assertSeeTextInOrder(
            ['Test item', 'Тестовое описание', '111.11'],
            true
        );
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
        $response->assertStatus(403);
        Auth::loginUsingId(1);
        $this->storeTestGoods();
        $this->assertDatabaseHas('goods', ['name' => 'Test item']);
        $response = $this->get('/goods');
        $response->assertSeeTextInOrder(['Test item'], true);
    }

    public function testEdit(): void
    {
        $response = $this->get('/goods/1/edit');
        $response->assertStatus(403);
        Auth::loginUsingId(1);
        $response = $this->get('/goods/1/edit');
        $this->assertSame(['id' => 1, 'name' => 'Тестовая категория'], $response['categories'][0]);
        $this->assertSame(['id' => 1, 'name' => 'Тестовая характеристика', 'value' => 'test_char_value'], $response['additCharacteristics'][0]);
    }

    public function testUpdate(): void
    {
        $response = $this->post(route('goods.update', 1), ['_method' => 'PATCH', null]);
        $response->assertStatus(403);
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
        $response->assertSeeTextInOrder(['Тестовый товар2'], true);
    }

    public function testDestroy(): void
    {
        Auth::loginUsingId(1);
        $this->post(route('goods.destroy', 1), ['_method' => 'DELETE']);
        $this->assertDatabaseMissing('goods', ['name' => 'Тестовый товар']);
        $response = $this->get('/goods');
        $response->assertSeeTextInOrder(["успешно удалён"], true);
    }

    private function storeTestGoods(): TestResponse
    {
        return $this->post(route('goods.store'), [
            'name' => 'Test item',
            'description' => 'Тестовое описание',
            'slug' => 'test',
            'price' => 111.11,
            'category_id' => 1
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
