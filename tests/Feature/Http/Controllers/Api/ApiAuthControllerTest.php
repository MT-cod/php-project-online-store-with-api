<?php

namespace Http\Controllers\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ApiAuthControllerTest extends TestCase
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

    public function testRegister(): void
    {
        $response = $this->json(
            'post',
            '/api/auth/register',
            ['name' => 'testUserName', 'email' => 'testUserEmail@test.com', 'password' => 'pasPAS123']
        );
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Пользователь успешно зарегистрирован. Токен выдан.']);
    }

    public function testLogin(): void
    {
        $this->json(
            'post',
            '/api/auth/register',
            ['name' => 'testUserName', 'email' => 'testUserEmail@test.com', 'password' => 'pasPAS123']
        );
        $response = $this->json(
            'post',
            '/api/auth/login',
            ['email' => 'testUserEmail@test.com', 'password' => 'pasPAS123']
        );
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Успешная авторизация. Токен выдан.']);

        $errorResp = $this->json(
            'post',
            '/api/auth/login',
            ['email' => 'bla@bla.bla', 'password' => 'blablabla']
        );
        $errorResp
            ->assertStatus(401)
            ->assertJsonFragment(['errors' => 'Не удалось авторизовать пользователя.']);
    }

    public function testUser(): void
    {
        $response = $this->get('/api/auth/user', ['Authorization' => 'Bearer ' . $this->testUserToken]);
        $response
            ->assertSuccessful();
    }

    public function testLogout(): void
    {
        $response = $this->get('/api/auth/logout', ['Authorization' => 'Bearer ' . $this->testUserToken]);
        $response
            ->assertSuccessful()
            ->assertJsonFragment(['success' => 'Успешный выход из системы.']);
    }
}
