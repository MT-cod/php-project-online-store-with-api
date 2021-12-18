<?php

namespace Http\Controllers\Api;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ApiCategoriesControllerTest extends TestCase
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

    public function testTree(): void
    {
        $response = $this->get('/api/categories/tree');
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Дерево категорий успешно получено.'])
            ->assertJsonFragment(['name' => 'Тестовая категория']);
    }

    public function testIndex(): void
    {
        $response = $this->get('/api/categories?filter[name]=&sort[name]=asc&perpage=10');
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Список категорий успешно получен.'])
            ->assertJsonFragment(['name' => 'Тестовая категория']);

        $errorResp = $this->get('/api/categories?filter[level]=bla');
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(['error' => ['filter.level' => ['Поле filter.level должно быть целым числом.']]]);
    }

    public function testShow(): void
    {
        $response = $this->get('/api/categories/1');
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Категория успешно получена.'])
            ->assertJsonFragment(['name' => 'Тестовая категория']);

        $errorResp = $this->get('/api/categories/0');
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(['error' => 'Не удалось найти категорию с id:0.']);
    }

    public function testStore(): void
    {
        $response = $this->json(
            'post',
            '/api/categories',
            ['name' => 'testCat', 'parent_id' => 2, 'description' => 'testDesc'],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Категория testCat успешно создана.'])
            ->assertJsonFragment(["name" => "testCat"]);

        $errorResp = $this->json(
            'post',
            '/api/categories',
            ['name' => 'testCat2', 'parent_id' => 3, 'description' => 'testDesc2'],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(
                ['error' => ['parent_id' => ['Категория не может быть подкатегорией категории 3-го уровня!']]]
            );
    }

    public function testUpdate(): void
    {
        $response = $this->json(
            'post',
            '/api/categories/1',
            ['_method' => 'PATCH', 'name' => 'testCatUpd', 'parent_id' => 0, 'description' => 'testDescUpd'],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Параметры категории успешно изменены.'])
            ->assertJsonFragment(["name" => "testCatUpd"]);

        $errorResp = $this->json(
            'post',
            '/api/categories/1',
            ['_method' => 'PATCH', 'parent_id' => 2],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(
                ['error' => ['parent_id' => [
                    'Категория не может стать категорией 3-го уровня, т.к. имеет дочернюю категорию!'
                ]]]
            );
    }

    public function testDestroy(): void
    {
        Category::create(['name' => 'testCat', 'description' => 'testDesc']);
        $response = $this->json(
            'post',
            '/api/categories/3',
            ['_method' => 'DELETE'],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => "Категория testCat успешно удалена."]);
        $this->assertDatabaseMissing('categories', ['id' => 3]);

        $errorResp = $this->json(
            'post',
            '/api/categories/1',
            ['_method' => 'DELETE'],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(
                ['error' => 'Не удалось удалить категорию Тестовая категория! У категории имеется подкатегория!']
            );
        $errorResp = $this->json(
            'post',
            '/api/categories/2',
            ['_method' => 'DELETE'],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(
                ['error' => 'Не удалось удалить категорию Тестовая категория 2! У категории есть товары!']
            );
        $errorResp = $this->json(
            'post',
            '/api/categories/222',
            ['_method' => 'DELETE'],
            ['Authorization' => 'Bearer ' . $this->testUserToken]
        );
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(
                ['error' => 'Не удалось найти категорию с id:222.']
            );
    }
}
