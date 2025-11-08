<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TranslationTest extends TestCase
{
    // This will automatically use the RefreshDatabase trait to refresh the database between tests
    use RefreshDatabase;

    /** @test */
    public function test_create_translation()
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => Hash::make('password')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'testuser@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $token = $response->json('token');

        $response = $this->postJson('/api/translations', [
            'locale' => 'en',
            'key' => 'aspernatur',
            'content' => 'Voluptates facere possimus et itaque.!',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(201);
    }

    /** @test */
    public function test_search_translations_by_key()
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => Hash::make('password')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'testuser@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $token = $response->json('token');

        $this->postJson('/api/translations', [
            'locale' => 'en',
            'key' => 'aspernatur',
            'content' => 'Voluptates facere possimus et itaque.!',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response = $this->getJson('/api/translations?key=aspernatur', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }

    /** @test */
    public function test_update_translation()
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => Hash::make('password')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'testuser@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $token = $response->json('token');

        $translation = $this->postJson('/api/translations', [
            'locale' => 'en',
            'key' => 'aspernatur',
            'content' => 'Voluptates facere possimus et itaque.!',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $translationId = $translation->json('id');

        $response = $this->putJson("/api/translations/{$translationId}", [
            'content' => 'Updated content for translation',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['content' => 'Updated content for translation']);  // Verify the updated content
    }

    /** @test */
    public function test_export_translations()
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => Hash::make('password')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'testuser@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $token = $response->json('token');

        $response = $this->getJson('/api/translations/export', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200);
    }
}


?>
