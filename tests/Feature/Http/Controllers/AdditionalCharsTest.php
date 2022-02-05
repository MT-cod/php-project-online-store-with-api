<?php

namespace Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AdditionalCharsTest extends TestCase
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
        $response = $this->get('/additionalChars');
        $response->assertOk();
        $this->assertContains('Тестовая характеристика', $response['additChars'][0]->toArray());
    }

    public function testStore(): void
    {
        $this->post(route('additionalChars.store'), [
            'name' => 'Тестовая характеристика 2',
            'value' => 'Тестовое описание 2'
        ]);
        $this->assertDatabaseHas('additional_chars', ['name' => 'Тестовая характеристика 2']);
    }

    public function testEdit(): void
    {
        $response = $this->get('/additionalChars/1/edit');
        $this->assertSame('Тестовая характеристика', $response['additChar']['name']);
    }

    public function testUpdate(): void
    {
        $this->post(route('additionalChars.update', 1), [
            '_method' => 'PATCH',
            'name' => 'Тестовая характеристика Upd'
        ]);
        $this->assertDatabaseHas('additional_chars', ['name' => 'Тестовая характеристика Upd']);
    }

    public function testDestroy(): void
    {
        $this->post(route('additionalChars.destroy', 1), ['_method' => 'DELETE']);
        $this->assertDatabaseMissing('additional_chars', ['name' => 'Тестовая характеристика']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
