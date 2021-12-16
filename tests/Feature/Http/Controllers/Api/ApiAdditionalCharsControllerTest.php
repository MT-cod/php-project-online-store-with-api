<?php

namespace Http\Controllers\Api;

use App\Models\AdditionalChar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ApiAdditionalCharsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('db:seed TestDatabaseSeeder');
    }

    public function testIndex(): void
    {
        $response = $this->get('/api/additionalChars?filter[value]=test_char_value&sort[value]=asc&perpage=10');
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Список доп характеристик успешно получен.'])
            ->assertJsonFragment(['name' => 'Тестовая характеристика']);
        $errorResp = $this->get('/api/additionalChars?sort[value]=bla');
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(['error' => ['sort.value' => ['Выбранное значение для sort.value ошибочно.']]]);
    }

    public function testShow(): void
    {
        $response = $this->get('/api/additionalChars/1');
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Доп характеристика успешно получена.'])
            ->assertJsonFragment(['name' => 'Тестовая характеристика']);
        $errorResp = $this->get('/api/additionalChars/0');
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(['error' => 'Не удалось найти доп характеристику с id:0.']);
    }

    public function testStore(): void
    {
        $response = $this->json(
            'post',
            '/api/additionalChars',
            ['name' => 'testChar2', 'value' => '']
        );
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Доп характеристика успешно создана.'])
            ->assertJsonFragment(['name' => 'testChar2']);

        $errorResp = $this->json(
            'post',
            '/api/additionalChars',
            ['name' => '', 'value' => '']
        );
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(['error' => ['name' => ['Поле Имя обязательно для заполнения.']]]);
    }

    public function testUpdate(): void
    {
        $response = $this->json(
            'post',
            '/api/additionalChars/1',
            ['_method' => 'PATCH', 'name' => 'testChar22', 'value' => 'testValue2']
        );
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Параметры доп характеристики успешно изменены.'])
            ->assertJsonFragment(['name' => 'testChar22']);

        $errorResp = $this->json(
            'post',
            '/api/additionalChars/0',
            ['_method' => 'PATCH']
        );
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(['error' => ['id' => ['Выбранное значение для id некорректно.']]]);
    }

    public function testDestroy(): void
    {
        $nameOfTestChar = AdditionalChar::find(1)->name;
        $response = $this->json('post', '/api/additionalChars/1', ['_method' => 'DELETE']);
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => "Характеристика $nameOfTestChar успешно удалена."]);
        $this->assertDatabaseMissing('additional_chars', ['name' => $nameOfTestChar]);

        $errorResp = $this->json('post', '/api/additionalChars/0', ['_method' => 'DELETE']);
        $errorResp
            ->assertStatus(400)
            ->assertJsonFragment(['error' => 'Не удалось найти доп характеристику с id:0']);
    }
}
