<?php

namespace Http\Controllers;

use App\Models\Category;
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
        Auth::loginUsingId(1);
        $response = $this->json(
            'post',
            '/categories',
            ['name' => 'testCat', 'parent_id' => 2, 'description' => 'testDesc']
        );
        $this->assertDatabaseHas('categories', ['name' => 'testCat']);

        $errorResp = $this->json(
            'post',
            '/categories',
            ['name' => 'testCat2', 'parent_id' => 3, 'description' => 'testDesc2']
        );
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(
                ['errors' => ['parent_id' => ['Категория не может быть подкатегорией категории 3-го уровня!']]]
            );
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
        Auth::loginUsingId(1);
        $response = $this->json(
            'post',
            '/categories/1',
            ['_method' => 'PATCH', 'name' => 'testCatUpd', 'parent_id' => 0, 'description' => 'testDescUpd']
        );
        $this->assertDatabaseHas('categories', ['name' => 'testCatUpd']);

        $errorResp = $this->json(
            'post',
            '/categories/1',
            ['_method' => 'PATCH', 'parent_id' => 2]
        );
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(
                ['errors' => ['parent_id' => [
                    'Категория не может стать категорией 3-го уровня, т.к. имеет дочернюю категорию!'
                ]]]
            );
    }

    public function testDestroy(): void
    {
        $errorResp = $this->post(route('categories.destroy', 1), ['_method' => 'DELETE']);
        $errorResp->assertStatus(403);

        Auth::loginUsingId(1);
        $this->post(route('categories.destroy', 1), ['_method' => 'DELETE']);
        $response = $this->get('/categories');
        $response->assertSeeTextInOrder(
            ['Не удалось удалить категорию "Тестовая категория"! У категории имеется подкатегория!'],
            true
        );

        $this->post(route('categories.destroy', 2), ['_method' => 'DELETE']);
        $response = $this->get('/categories');
        $response->assertSeeTextInOrder(
            ['Не удалось удалить категорию "Тестовая категория 2"! У категории есть товары!'],
            true
        );

        Category::create(['name' => 'testCat', 'description' => 'testDesc']);
        $this->post(route('categories.destroy', 3), ['_method' => 'DELETE']);
        $this->assertDatabaseMissing('categories', ['id' => 3]);
        $response = $this->get('/categories');
        $response->assertSeeTextInOrder(['Категория "testCat" успешно удалена.'], true);
    }
}
