<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        $response->assertSeeTextInOrder(
            ['Тестовая характеристика'],
            true
        );
    }

    public function testStore(): void
    {
        $this->post(route('additionalChars.store'), [
            'name' => 'Тестовая характеристика 2',
            'value' => 'Тестовое описание 2'
        ]);
        $this->assertDatabaseHas('additional_chars', ['name' => 'Тестовая характеристика 2']);
        $response = $this->get('/additionalChars');
        $response->assertSeeTextInOrder(['Тестовая характеристика 2'], true);
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
            'name' => 'Тестовая характеристика Upd',
            'parent_id' => 0
        ]);
        $this->assertDatabaseHas('additional_chars', ['name' => 'Тестовая характеристика Upd']);
        $response = $this->get('/additionalChars');
        $response->assertSeeTextInOrder(['Тестовая характеристика Upd'], true);
    }

    public function testDestroy(): void
    {
        $response = $this->post(route('additionalChars.destroy', 1), ['_method' => 'DELETE']);
        $this->assertDatabaseMissing('additional_chars', ['name' => 'Тестовая характеристика']);
        $response = $this->get('/additionalChars');
        $response->assertSeeTextInOrder([`Характеристика "Тестовая характеристика" успешно удалена`], true);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
