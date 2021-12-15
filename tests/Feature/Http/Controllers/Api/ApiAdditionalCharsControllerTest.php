<?php

namespace Http\Controllers\Api;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ApiAdditionalCharsControllerTest extends TestCase
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

    /*public function testStore(): void
    {
    }

    public function testUpdate(): void
    {
    }

    public function testDestroy(): void
    {
    }

    public function testShow(): void
    {
    }*/

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
