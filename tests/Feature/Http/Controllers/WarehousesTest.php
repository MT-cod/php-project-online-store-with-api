<?php

namespace Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class WarehousesTest extends TestCase
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
        $response = $this->get('/warehouses');
        $response->assertOk();
        $this->assertContains('Test warehouse', $response['warehouses'][0]->toArray());
    }

    public function testStore(): void
    {
        Auth::loginUsingId(1);
        $this->post(route('warehouses.store'), [
            'name' => 'Test warehouse 2',
            'description' => 'Тестовое описание склада 2',
            'address' => '',
            'priority' => 33
        ]);
        $this->assertDatabaseHas('warehouses', ['name' => 'Test warehouse 2']);
    }

    public function testEdit(): void
    {
        Auth::loginUsingId(1);
        $response = $this->get('/warehouses/1/edit');
        $this->assertSame('Test warehouse', $response['warehouse']['name']);
    }

    public function testUpdate(): void
    {
        Auth::loginUsingId(1);
        $this->post(route('warehouses.update', 1), [
            '_method' => 'PATCH',
            'name' => 'Test warehouse Upd'
        ]);
        $this->assertDatabaseHas('warehouses', ['name' => 'Test warehouse Upd']);
    }

    public function testDestroy(): void
    {
        Auth::loginUsingId(1);
        $this->post(route('warehouses.destroy', 1), ['_method' => 'DELETE']);
        $this->assertDatabaseMissing('warehouses', ['name' => 'Test warehouse']);
    }
}
