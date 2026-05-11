<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_json(): void
    {
        Category::create(['name' => 'A']);
        Category::create(['name' => 'B']);

        $response = $this->getJson('/api/categories');

        $response->assertOk()->assertJsonCount(2);
    }

    public function test_store_creates_category(): void
    {
        $response = $this->postJson('/api/categories', [
            'name' => 'Pecho API',
        ]);

        $response->assertCreated()
            ->assertJsonPath('name', 'Pecho API');
        $this->assertDatabaseHas('categories', ['name' => 'Pecho API']);
    }

    public function test_store_validation_error(): void
    {
        $response = $this->postJson('/api/categories', []);

        $response->assertUnprocessable()->assertJsonValidationErrors(['name']);
    }

    public function test_show_update_destroy(): void
    {
        $category = Category::create(['name' => 'Original']);

        $this->getJson('/api/categories/'.$category->id)
            ->assertOk()
            ->assertJsonPath('name', 'Original');

        $this->putJson('/api/categories/'.$category->id, ['name' => 'Actualizado'])
            ->assertOk()
            ->assertJsonPath('name', 'Actualizado');

        $this->deleteJson('/api/categories/'.$category->id)
            ->assertOk();

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
