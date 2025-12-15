<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HealthControllerTest extends TestCase
{
    /** @test */
    public function api_health_endpoint_returns_ok_status(): void
    {
        // Act : call HTTP GET /api/health
        $response = $this->getJson('/api/health');

        // Assert : status HTTP 200
        $response->assertStatus(200);

        // Assert : JSON response contains 'status' => 'ok'
        $response->assertJson([
            'status' => 'ok',
        ]);
    }
}
