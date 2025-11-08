<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExportPerformanceTest extends TestCase
{
  /** @test */
    public function export_should_complete_in_under_500ms()
    {
        $response = $this->getJson('/api/translations/export');

        $response->assertStatus(200);

        // Assert that the request completes within 500 milliseconds
        $response->assertTimeLessThan(500);
    }
}
