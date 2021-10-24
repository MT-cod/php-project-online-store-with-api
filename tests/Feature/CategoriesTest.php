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

class CategoriesTest extends TestCase
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
        $response = $this->get('/categories');
        $response->assertOk();
        $response->assertSeeTextInOrder(
            ['Тестовая категория'],
            true
        );
    }

    public function testCreate(): void
    {
        $response = $this->get('/categories/create');
        $this->assertSame(['id' => 1, 'name' => 'Тестовая категория'], $response['categories'][0]);
    }

    public function testStore(): void
    {
        $response = $this->storeTestCategories();
        $response->assertStatus(403);
        Auth::loginUsingId(1);
        $this->storeTestCategories();
        $this->assertDatabaseHas('categories', ['name' => 'Тестовая категория']);
        $response = $this->get('/categories');
        $response->assertSeeTextInOrder(['Тестовая категория'], true);
    }

    public function testEdit(): void
    {
        $response = $this->get('/categories/1/edit');
        $response->assertStatus(403);
        Auth::loginUsingId(1);
        $response = $this->get('/categories/1/edit');
        $this->assertSame(['id' => 1, 'name' => 'Тестовая категория'], $response['categories'][0]);
    }

    public function testUpdate(): void
    {
        $response = $this->post(route('categories.update', 1), ['_method' => 'PATCH', null]);
        $response->assertStatus(403);
        Auth::loginUsingId(1);
        $this->post(route('categories.update', 1), [
            '_method' => 'PATCH',
            'name' => 'Тестовая категория Upd',
            'parent_id' => 0
        ]);
        $this->assertDatabaseHas('categories', ['name' => 'Тестовая категория Upd']);
        $response = $this->get('/categories');
        $response->assertSeeTextInOrder(['Тестовая категория Upd'], true);
    }

    public function testDestroy(): void
    {
        $response = $this->post(route('categories.destroy', 1), ['_method' => 'DELETE']);
        $response->assertStatus(403);
        Auth::loginUsingId(1);
        $response = $this->post(route('categories.destroy', 1), ['_method' => 'DELETE']);
        $response->assertStatus(500);
        $response = $this->post(route('categories.destroy', 2), ['_method' => 'DELETE']);
        $response->assertStatus(500);
        $this->post(route('goods.destroy', 1), ['_method' => 'DELETE']);
        $this->post(route('categories.destroy', 2), ['_method' => 'DELETE']);
        $this->assertDatabaseMissing('categories', ['name' => 'Тестовая категория 2']);
        $response = $this->get('/categories');
        $response->assertSeeTextInOrder(['Категория "Тестовая категория 2" успешно удалена'], true);
    }

    private function storeTestCategories(): TestResponse
    {
        return $this->post(route('categories.store'), [
            'name' => 'Тестовая категория',
            'description' => 'Тестовое описание',
            'parent_id' => 0
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
